<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Bag;
use Bag\Collection;
use Bag\Eloquent\Casts\AsBag;
use Bag\Eloquent\Casts\AsBagCollection;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

trait WithEloquentCasting
{
    /**
     * @param array<string|int> $arguments
     * @return CastsAttributes<Bag, array<mixed>>
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new AsBag(static::class, $arguments);
    }

    public static function castAsCollection(): string
    {
        return AsBagCollection::of(static::class);
    }

    /**
     * @return ($key is null ? mixed : Collection)
     */
    abstract public function getRaw(?string $key = null): mixed;
}
