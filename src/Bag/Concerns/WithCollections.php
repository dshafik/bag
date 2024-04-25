<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes;
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
        static $cache = [];

        if (isset($cache[static::class])) {
            return $cache[static::class]::make($values)->map(fn ($value): static => static::from($value));
        }

        $cache[static::class] = Collection::class;

        $collectionAttributes = (new ReflectionClass(static::class))->getAttributes(Attributes\Collection::class);
        if (count($collectionAttributes) > 0) {
            $cache[static::class] = $collectionAttributes[0]->newInstance()->collectionClass;
        }

        return $cache[static::class]::make($values)->map(fn ($value): static => static::from($value));
    }
}
