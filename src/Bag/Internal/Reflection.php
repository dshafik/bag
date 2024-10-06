<?php

declare(strict_types=1);

namespace Bag\Internal;

use Bag\Attributes\Attribute;
use Bag\Bag;
use Bag\Collection;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use SensitiveParameter;

/**
 * @template T of Bag
 * @internal This class is not meant to be used or overwritten outside of the library.
 */
class Reflection
{
    /**
     * @param class-string<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>>|T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>|ReflectionClass<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>> $classOrObject
     * @return ReflectionClass<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>>
     */
    public static function getClass(string|object $classOrObject): ReflectionClass
    {
        if ($classOrObject instanceof ReflectionClass) {
            return $classOrObject;
        }

        return new ReflectionClass($classOrObject);
    }

    /**
     * @param class-string<T>|T|ReflectionClass<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>> $classOrObject
     */
    public static function getConstructor(string|object $classOrObject): ReflectionMethod|null
    {
        if ($classOrObject instanceof ReflectionClass) {
            return $classOrObject->getConstructor();
        }

        return self::getClass($classOrObject)->getConstructor();
    }

    /**
     * @param ReflectionClass<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>>|T|class-string<T>|null $classOrObject
     * @return array<ReflectionProperty>
     */
    public static function getProperties(ReflectionClass|Bag|string|null $classOrObject): array
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
     * @template TAttr of Attribute|SensitiveParameter
     * @param ReflectionClass<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>>|ReflectionMethod|ReflectionProperty|ReflectionParameter|null $reflectionObject
     * @param ?class-string<TAttr> $attribute
     * @return array<ReflectionAttribute<TAttr>>
     */
    public static function getAttributes(
        ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionParameter|null $reflectionObject,
        ?string $attribute,
        int $flags = 0
    ): array {
        if ($reflectionObject === null) {
            return [];
        }

        return $reflectionObject->getAttributes($attribute, $flags);
    }

    /**
     * @template TAttr of Attribute|SensitiveParameter
     * @param ReflectionClass<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>>|ReflectionMethod|ReflectionProperty|ReflectionParameter|null $reflectionObject
     * @param ?class-string<TAttr> $attribute
     * @return ReflectionAttribute<TAttr>|null
     */
    public static function getAttribute(
        ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionParameter|null $reflectionObject,
        ?string $attribute,
        int $flags = 0
    ): ReflectionAttribute|null {
        $attributes = self::getAttributes($reflectionObject, $attribute, $flags);

        if (count($attributes) === 0) {
            return null;
        }

        return $attributes[0];
    }

    /**
     * @template TAttr of Attribute|SensitiveParameter
     * @param ReflectionClass<T|Collection<array-key, mixed>|LaravelCollection<array-key, mixed>>|ReflectionMethod|ReflectionProperty|ReflectionParameter|ReflectionAttribute<TAttr>|null $reflectionObject
     * @param class-string<TAttr> $attribute
     * @return TAttr|null
     */
    public static function getAttributeInstance(
        ReflectionClass|ReflectionMethod|ReflectionProperty|ReflectionParameter|ReflectionAttribute|null $reflectionObject,
        ?string $attribute = null,
        int $flags = 0
    ): Attribute|SensitiveParameter|null {
        if (!($reflectionObject instanceof ReflectionAttribute)) {
            $reflectionObject = self::getAttribute($reflectionObject, $attribute, $flags);
        }

        if ($reflectionObject === null) {
            return null;
        }

        return $reflectionObject->newInstance();
    }

    /**
     * @template TAttr of Attribute|SensitiveParameter
     * @param ReflectionAttribute<TAttr> $reflectionAttribute
     * @return array<array-key,mixed>
     */
    public static function getAttributeArguments(ReflectionAttribute $reflectionAttribute): array
    {
        return $reflectionAttribute->getArguments();
    }
}
