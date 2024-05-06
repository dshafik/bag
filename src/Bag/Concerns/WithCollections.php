<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Collection as CollectionAttribute;
use Bag\Bag;
use Bag\Collection;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;

trait WithCollections
{
    /**
     * @param  iterable<int, mixed>  $values
     * @return Collection<static>
     */
    public static function collect(iterable $values = []): Collection
    {
        $collection = Cache::remember(__METHOD__, static::class, function (): string {
            return Reflection::getAttributeInstance(
                Reflection::getClass(static::class),
                CollectionAttribute::class
            )?->collectionClass ?? Collection::class;
        });

        return ($collection)::make($values)->map(fn ($value): Bag => $value instanceof Bag ? $value : static::from($value));
    }
}
