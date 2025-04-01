<?php

declare(strict_types=1);

namespace Bag\TypeScript;

use Bag\Bag;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Bag\TypeScript\Reflection\BagReflectionProperty;
use Exception;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionProperty;
use Spatie\TypeScriptTransformer\Structures\MissingSymbolsCollection;
use Spatie\TypeScriptTransformer\Transformers\DtoTransformer;

// @phpstan-ignore class.notFound
if (class_exists(DtoTransformer::class)) {
    class BagTransformer extends DtoTransformer
    {
        /**
         * @param ReflectionClass<Bag> $class
         * @return array<BagReflectionProperty>
         */
        protected function resolveProperties(ReflectionClass $class): array
        {
            /** @var array<BagReflectionProperty> $properties */
            // @phpstan-ignore-next-line
            $properties = collect(parent::resolveProperties($class))->map(function (ReflectionProperty $property) {
                return new BagReflectionProperty($property->getDeclaringClass()->getName(), $property->getName());
            })->toArray();

            return $properties;
        }

        // @phpstan-ignore class.notFound
        protected function transformPropertyName(ReflectionProperty $property, MissingSymbolsCollection $missingSymbols): string
        {
            $pipe = new ProcessParameters();
            /** @var class-string<Bag> $bagClassname */
            $bagClassname = $property->getDeclaringClass()->getName();
            $aliases = $pipe(new BagInput($bagClassname, Collection::empty()))->params->aliases();

            // @phpstan-ignore class.noParent
            $name = parent::transformPropertyName($property, $missingSymbols);

            return $aliases['output'][$name] ?? $name;
        }
    }
} else {
    class BagTransformer
    {
        public function __construct()
        {
            throw new Exception('You must install the spatie/typescript-transformer or spatie/laravel-typescript-transformer package to use this class');
        }
    }
}
