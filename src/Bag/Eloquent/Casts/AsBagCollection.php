<?php

declare(strict_types=1);

namespace Bag\Eloquent\Casts;

use Bag\Bag;
use Bag\Collection;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @implements CastsAttributes<Collection, mixed>
 */
readonly class AsBagCollection implements CastsAttributes
{
    public function __construct(protected string $bagClass)
    {
    }

    /**
     * @param ?string $value
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Bag|LaravelCollection|null
    {
        if ($value === null) {
            return null;
        }

        return ($this->bagClass)::collect(json_decode($value, true));
    }

    /**
     * @param Arrayable<(int|string), mixed>|iterable<(int|string), mixed>|null $value
     * @param array<array-key,mixed> $attributes
     * @return array<string|mixed>|null
     */
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
