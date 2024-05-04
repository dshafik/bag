<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes;
use Bag\Cache;
use Bag\Collection;
use ReflectionClass;

trait WithCollections
{
    /**
     * @param  iterable<int, mixed>  $values
     * @return Collection<static>
     */
    public static function collect(iterable $values = []): Collection
    {
        $collection = Cache::remember(__METHOD__, static::class, function (): string {
            $collection = Collection::class;

            $collectionAttributes = (new ReflectionClass(static::class))->getAttributes(Attributes\Collection::class);
            if (count($collectionAttributes) > 0) {
                $collection = $collectionAttributes[0]->newInstance()->collectionClass;
            }

            return $collection;
        });

        return ($collection)::make($values)->map(fn ($value): static => static::from($value));
    }
}
