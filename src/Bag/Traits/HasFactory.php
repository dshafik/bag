<?php

declare(strict_types=1);

namespace Bag\Traits;

use Bag\Attributes\Factory as FactoryAttribute;
use Bag\Cache;
use Bag\Collection;
use Bag\Exceptions\MissingFactoryException;
use Bag\Factory;
use Bag\Reflection;

trait HasFactory
{
    /**
     * @throws MissingFactoryException
     */
    public static function factory(Collection|array|int $data = []): Factory
    {
        $count = 1;
        if (\is_int($data)) {
            $count = $data;
            $data = [];
        }

        /** @var Factory $factory */
        $factory = new (static::getFactoryClass())(static::class, $data);

        if ($count > 1) {
            $factory->count($count);
        }

        return $factory;
    }

    protected static function getFactoryClass()
    {
        return Cache::remember(__METHOD__, static::class, function () {
            $factoryAttribute = Reflection::getAttributeInstance(Reflection::getClass(static::class), FactoryAttribute::class);
            if ($factoryAttribute === null) {
                throw new MissingFactoryException(sprintf('Bag "%s" missing factory attribute', static::class));
            }

            if (!\class_exists($factoryAttribute->factoryClass)) {
                throw new MissingFactoryException(sprintf('Factory class "%s" for Bag "%s" not found', $factoryAttribute->factoryClass, static::class));
            }

            return $factoryAttribute->factoryClass;
        });
    }
}
