<?php

declare(strict_types=1);

namespace Bag\Internal;

use Bag\Bag;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class Reflection
{
    public static function getClass(string|object $classOrObject): ReflectionClass
    {
        if ($classOrObject instanceof ReflectionClass) {
            return $classOrObject;
        }

        return new ReflectionClass($classOrObject);
    }

    public static function getConstructor(string|object $classOrObject): ReflectionMethod|null
    {
        if ($classOrObject instanceof ReflectionClass) {
            return $classOrObject->getConstructor();
        }

        return self::getClass($classOrObject)->getConstructor();
    }

    /**
     * @return array<ReflectionProperty>
     */
    public static function getProperties(ReflectionClass|Bag|LaravelCollection|string|null $classOrObject): array
    {
        if ($classOrObject === null) {
            return [];
        }

        return self::getClass($classOrObject)->getProperties();
    }

    /**
     * @return array<ReflectionParameter>
     */
    public static function getParameters(?ReflectionMethod $reflectionMethod): array
    {
        if ($reflectionMethod === null) {
            return [];
        }

        return $reflectionMethod->getParameters();
    }

    /**
     * @return array<ReflectionAttribute>
     */
    public static function getAttributes(
        ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionParameter|null $reflectionObject,
        string $attribute,
        int $flags = 0
    ): array {
        if ($reflectionObject === null) {
            return [];
        }

        return $reflectionObject->getAttributes($attribute, $flags);
    }

    public static function getAttribute(
        ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionParameter|null $reflectionObject,
        string $attribute,
        int $flags = 0
    ): ReflectionAttribute|null {
        $attributes = self::getAttributes($reflectionObject, $attribute, $flags);

        if (count($attributes) === 0) {
            return null;
        }

        return $attributes[0];
    }

    public static function getAttributeInstance(
        ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionParameter|ReflectionAttribute|null $reflectionObject,
        ?string $attribute = null,
        int $flags = 0
    ): ?object {
        if (!($reflectionObject instanceof ReflectionAttribute)) {
            $reflectionObject = self::getAttribute($reflectionObject, $attribute, $flags);
        }

        if ($reflectionObject === null) {
            return null;
        }

        return $reflectionObject->newInstance();
    }

    public static function getAttributeArguments(ReflectionAttribute $reflectionAttribute): array
    {
        return $reflectionAttribute->getArguments();
    }
}
