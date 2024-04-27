<?php

declare(strict_types=1);

namespace Bag\Concerns;

use ArrayAccess;
use Bag\Attributes\Transforms;
use Bag\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Support\Str;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use TypeError;

trait WithTransformers
{
    public const FROM_JSON = 'json';
    protected const array TYPE_ALIASES = [
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

    protected static function transform(mixed $from): ArrayAccess|iterable|Collection|LaravelCollection|Arrayable
    {
        $methods = collect((new ReflectionClass(static::class))->getMethods(ReflectionMethod::IS_STATIC))->filter(function (ReflectionMethod $method) use ($from) {
            return collect($method->getAttributes(Transforms::class))->map(fn ($attribute) => $attribute->newInstance())->filter(function (Transforms $transformer) use ($from) {
                $types = $transformer->types->filter(function (string $type) use ($from) {
                    $fromType = gettype($from);
                    return match (true) {
                        $type === 'json' && is_string($from) && Str::isJson($from) => true,
                        is_object($from) && $from::class === $type => true,
                        is_object($from) && is_a($from, $type, true) => true,
                        isset(self::TYPE_ALIASES[$type]) && self::TYPE_ALIASES[$type] === $fromType => true,
                        default => false,
                    };
                });
                return $types->isNotEmpty();
            })->isNotEmpty();
        });

        if ($methods->containsOneItem()) {
            /** @var ReflectionMethod $method */
            $method = $methods->first();
            return $method->invoke(null, $from);
        }

        if ($methods->count() > 1) {
            $method = static::findMethod($methods, $from);

            return $method->invoke(null, $from);
        }

        if (is_array($from) || is_iterable($from) || $from instanceof ArrayAccess || $from instanceof Arrayable || is_iterable($from)) {
            return $from;
        }

        throw new TypeError(sprintf('%s::from(): Argument #1 ($values): must be of type ArrayAccess|Traversable|Collection|LaravelCollection|Arrayable|array, %s given', static::class, gettype($from)));
    }

    protected static function findMethod(LaravelCollection $methods, mixed $from): ReflectionMethod
    {
        /** @var Collection<string, array{transformer: Transforms, method: ReflectionMethod}> $transformers */
        $transformers = collect();
        $methods->each(function (ReflectionMethod $method) use (&$transformers) {
            collect($method->getAttributes(Transforms::class))->each(function (ReflectionAttribute $attribute) use (&$transformers, $method) {
                $transformer = $attribute->newInstance();
                $transformer->types->each(function (string $type) use (&$transformers, $transformer, $method) {
                    $transformers[$type] = ['transformer' => $transformer, 'method' => $method];
                });
            });
        });

        return $transformers->mapWithKeys(function (array $transformerMethod, string $type) use ($from) {
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
                $classes = $classes->merge(collect($parents)->values());
            }

            $implements = \class_implements($from);
            if ($implements) {
                $classes = $classes->merge(collect($implements)->values());
            }

            $key = $classes->search($type);
            if ($key === false) {
                return [$type => $transformerMethod['method']];
            }
            return [$key => $transformerMethod['method']];
        })->sortKeys()->first();
    }

    abstract public static function from(mixed $values): static;
}
