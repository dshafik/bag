<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Cache;
use Bag\Exceptions\InvalidBag;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Bag\Reflection;
use ReflectionClass;
use ReflectionParameter;

trait WithProperties
{
    protected static function getProperties(ReflectionClass $class): ValueCollection
    {
        return Cache::remember(__METHOD__, static::class, function () use ($class) {
            /** @var ValueCollection $properties */
            $properties = ValueCollection::make(Reflection::getParameters(Reflection::getConstructor($class)))->mapWithKeys(function (ReflectionParameter $property) use ($class) {
                return [$property->getName() => Value::create($class, $property)]; // @codeCoverageIgnore
            });

            if ($properties === null || $properties->count() === 0) {
                throw new InvalidBag(sprintf('Bag "%s" must have a constructor with at least one property', static::class));
            }

            return $properties;
        });
    }
}
