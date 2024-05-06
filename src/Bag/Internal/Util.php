<?php

declare(strict_types=1);

namespace Bag\Internal;

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
    public static function getPropertyType(ReflectionParameter|ReflectionProperty $property): ReflectionNamedType
    {
        $type = $property->getType();
        if ($type === null) {
            $type = (new ReflectionClosure(closure: fn (mixed $arg) => null))->getParameters()[0]->getType();
        }

        if ($type instanceof ReflectionIntersectionType) {
            throw new InvalidPropertyType(message: sprintf('Intersection types are not supported for parameter %s', $property->getName()));
        }

        if ($type instanceof ReflectionUnionType) {
            $type = $type->getTypes()[0];
        }

        /** @var ReflectionNamedType $type */
        return $type;
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
