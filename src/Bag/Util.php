<?php

declare(strict_types=1);

namespace Bag;

use Laravel\SerializableClosure\Support\ReflectionClosure;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ReflectionUnionType;
use RuntimeException;

class Util
{
    public static function getPropertyType(ReflectionParameter|ReflectionProperty $property): ReflectionNamedType
    {
        $type = $property->getType();
        if ($type === null) {
            $type = (new ReflectionClosure(closure: fn (mixed $arg) => null))->getParameters()[0]->getType();
        }

        if ($type instanceof ReflectionIntersectionType) {
            throw new RuntimeException(message: 'Intersection types are not supported for parameter {$name}');
        }

        if ($type instanceof ReflectionUnionType) {
            $type = $type->getTypes()[0];
        }

        /** @var ReflectionNamedType $type */
        return $type;
    }
}
