<?php

declare(strict_types=1);

namespace Bag\Internal;

use Bag\Collection;
use Bag\Exceptions\InvalidPropertyType;
use Illuminate\Foundation\Application;
use Illuminate\Pipeline\Pipeline;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionUnionType;

class Util
{
    /**
     * @return Collection<string>
     */
    public static function getPropertyTypes(ReflectionParameter|ReflectionProperty $property): Collection
    {
        $type = $property->getType();
        if ($type === null) {
            $type = (new ReflectionClosure(closure: fn (mixed $arg) => null))->getParameters()[0]->getType();
        }

        if ($type instanceof ReflectionIntersectionType) {
            throw new InvalidPropertyType(message: sprintf('Intersection types are not supported for parameter %s', $property->getName()));
        }

        if ($type instanceof ReflectionUnionType) {
            $type = $type->getTypes();
        }

        /** @var ReflectionNamedType[]|ReflectionNamedType $type */
        return Collection::wrap($type)->map(function ($type) {
            /** @var ReflectionNamedType $type */
            return $type->getName();
        })->when(is_callable([$type, 'allowsNull']) && $type->allowsNull(), function (Collection $types) {
            return $types->push('null');
        });
    }

    public static function getPipeline(): Pipeline
    {
        return Cache::remember(Pipeline::class, 'pipeline', function () {
            if (\function_exists('app')) {
                $container = app();
            } else {
                $container = new Application();
            }

            return new Pipeline($container);
        });
    }
}
