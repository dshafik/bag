<?php

declare(strict_types=1);

namespace Bag\Eloquent\Casts;

use Bag\Bag;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AsBagCollection implements CastsAttributes
{
    public function __construct(protected string $bagClass)
    {
    }

    public function get(Model $model, string $key, mixed $value, array $attributes): Bag|Collection|null
    {
        if ($value === null) {
            return null;
        }

        return ($this->bagClass)::collect(json_decode($value, true));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): array|null
    {
        if ($value === null) {
            return null;
        }

        return [$key => json_encode(collect($value)->map(fn (Bag $bag) => $bag->getRaw())->all())];
    }

    /**
     * @param class-string<Bag> $bagClass
     */
    public static function of(string $bagClass): string
    {
        return static::class .':'. $bagClass;
    }
}
