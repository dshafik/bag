<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Bag;
use Bag\Eloquent\Casts\AsBagCollection;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

trait WithEloquentCasting
{
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class (static::class, $arguments) implements CastsAttributes {
            /**
             * @param class-string<Bag> $bagClass
             */
            public function __construct(protected string $bagClass, protected array $arguments)
            {
            }

            public function get($model, string $key, $value, array $attributes): Bag|null
            {
                if ($value === null) {
                    return null;
                }

                return ($this->bagClass)::from(json_decode($value, true));
            }

            /**
             * @param Bag $value
             */
            public function set($model, string $key, $value, array $attributes): array|null
            {
                if ($value === null) {
                    return null;
                }

                if ($value instanceof Bag) {
                    return [$key => json_encode($value->getRaw())];
                }

                return [$key => json_encode($value)];
            }
        };
    }

    public static function castAsCollection(): string
    {
        return AsBagCollection::of(static::class);
    }

    abstract public function getRaw(?string $key = null): mixed;
}
