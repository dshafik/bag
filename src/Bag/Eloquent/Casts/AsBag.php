<?php

declare(strict_types=1);

namespace Bag\Eloquent\Casts;

use Bag\Bag;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/**
 * @implements CastsAttributes<Bag, mixed>
 */
class AsBag implements CastsAttributes
{
    /**
     * @param class-string<Bag> $bagClass
     * @param array<array-key,string|int> $arguments
     */
    public function __construct(protected string $bagClass, protected array $arguments)
    {
    }

    /**
     * @param ?string $value
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): Bag|null
    {
        if ($value === null) {
            return null;
        }

        return ($this->bagClass)::from(json_decode($value, true));
    }

    /**
     * @param Bag $value
     * @return array<string, mixed>|null
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array|null
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return [$key => json_encode($value)];
        }

        return [$key => json_encode($value->getRaw())];
    }
}
