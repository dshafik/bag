<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use ArrayAccess;
use Bag\Attributes\Transforms;
use Bag\Bag;
use Bag\Collection;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use TypeError;

readonly class Transform
{
    protected const TYPE_ALIASES = [
        'bool' => 'boolean',
        'boolean' => 'boolean',
        'int' => 'integer',
        'integer' => 'integer',
        'float' => 'double',
        'double' => 'double',
        'string' => 'string',
        'array' => 'array',
        'object' => 'object',
    ];

    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        if ($input->input->count() > 1 || !ctype_digit((string) $input->input->keys()->first())) {
            return $input;
        }

        $inputs = $input->input->first();

        $methods = collect(Reflection::getClass($input->bagClassname)->getMethods(ReflectionMethod::IS_STATIC))->filter(function (ReflectionMethod $method) use ($inputs) {
            return collect(Reflection::getAttributes($method, Transforms::class))->map(function ($attribute) {
                /** @var ReflectionAttribute<Transforms> $attribute */
                return Reflection::getAttributeInstance($attribute);
            })->filter(function (?Transforms $transformer) use ($inputs): bool {
                $types = $transformer?->types->filter(function (string $type) use ($inputs) {
                    $fromType = gettype($inputs);

                    return match (true) {
                        $type === 'json' && is_string($inputs) && Str::isJson($inputs) => true,
                        is_object($inputs) && $inputs::class === $type => true,
                        is_object($inputs) && is_a($inputs, $type, true) => true,
                        isset(self::TYPE_ALIASES[$type]) && self::TYPE_ALIASES[$type] === $fromType => true,
                        default => false,
                    };
                });

                return $types?->isNotEmpty() ?? false;
            })->isNotEmpty();
        });

        if ($methods->containsOneItem()) {
            /** @var ReflectionMethod $method */
            $method = $methods->first();


            $input->input = LaravelCollection::wrap($method->invoke(null, $inputs));

            return $input;
        }

        if ($methods->count() > 1) {
            $method = $this->findMethod($methods, $inputs);

            $input->input = LaravelCollection::wrap($method->invoke(null, $inputs));

            return $input;
        }

        if (is_array($inputs) || is_iterable($inputs) || $inputs instanceof ArrayAccess || $inputs instanceof Arrayable || is_iterable($inputs)) {
            /** @var ReflectionNamedType $parameterType */
            $parameterType = Reflection::getConstructor($input->bagClassname)?->getParameters()[0]->getType();
            // @phpstan-ignore-next-line
            if ($parameterType instanceof ReflectionUnionType && collect($parameterType->getTypes())->map(fn (ReflectionNamedType $type) => $type->getName())->contains('array')) {
                return $input;
            }

            if ($parameterType instanceof ReflectionNamedType && $parameterType->getName() === 'array') {
                return $input;
            }

            $input->input = LaravelCollection::wrap($inputs);

            return $input;
        }

        throw new TypeError(sprintf('%s::from(): Argument #1 ($values): must be of type ArrayAccess|Traversable|Collection|LaravelCollection|Arrayable|array, %s given', $input->bagClassname, gettype($inputs)));
    }

    /**
     * @param LaravelCollection<array-key, ReflectionMethod> $methods
     */
    protected function findMethod(LaravelCollection $methods, mixed $from): ReflectionMethod
    {
        /** @var Collection<string, array{transformer: Transforms, method: ReflectionMethod}> $transformers */
        $transformers = collect();
        $methods->each(function (ReflectionMethod $method) use (&$transformers) {
            collect(Reflection::getAttributes($method, Transforms::class))->each(function ($attribute) use (&$transformers, $method) {
                /** @var ReflectionAttribute<Transforms> $attribute */
                $transformer = Reflection::getAttributeInstance($attribute);
                $transformer?->types->each(function (string $type) use (&$transformers, $transformer, $method) {
                    $transformers[$type] = ['transformer' => $transformer, 'method' => $method];
                });
            });
        });

        /** @var ReflectionMethod $firstTransformer */
        $firstTransformer = $transformers->mapWithKeys(function (mixed $transformerMethod, string $type) use ($from): array {
            if (!is_array($transformerMethod)) {
                return [];
            }

            if (!is_object($from)) {
                return match (true) {
                    $type === 'json' && is_string($from) && Str::isJson($from) => [0 => $transformerMethod['method']],
                    isset(self::TYPE_ALIASES[$type]) && self::TYPE_ALIASES[$type] === gettype($from) => [1 => $transformerMethod['method']],
                    default => [2 => $transformerMethod['method']],
                };
            }

            /** @var array{transformer: Transforms, method: ReflectionMethod} $transformerMethod */
            $classes = collect([$from::class]);

            $parents = class_parents($from);
            if ($parents) {
                // @phpstan-ignore argument.type
                $classes = $classes->merge(collect($parents)->values());
            }

            $implements = \class_implements($from);
            if ($implements) {
                // @phpstan-ignore argument.type
                $classes = $classes->merge(collect($implements)->values());
            }

            $key = $classes->search(fn (string $value) => $type === $value);
            if ($key === false) {
                return [$type => $transformerMethod['method']];
            }

            return [$key => $transformerMethod['method']];
        })->sortKeys()->first();

        return $firstTransformer;
    }
}
