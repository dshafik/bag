<?php

declare(strict_types=1);

namespace Bag\Traits;

use Bag\Attributes\Factory as FactoryAttribute;
use Bag\Cache;
use Bag\Collection;
use Bag\Exceptions\MissingFactoryException;
use Bag\Factory;
use ReflectionClass;

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
            $factoryAttributes = (new ReflectionClass(static::class))->getAttributes(FactoryAttribute::class);
            if (count($factoryAttributes) === 0) {
                throw new MissingFactoryException(sprintf('Bag "%s" missing factory attribute', static::class));
            }


            $factoryClass = $factoryAttributes[0]->newInstance()->factoryClass;

            if (!\class_exists($factoryClass)) {
                throw new MissingFactoryException(sprintf('Factory class "%s" for Bag "%s" not found', $factoryClass, static::class));
            }

            return $factoryClass;
        });
    }
}
