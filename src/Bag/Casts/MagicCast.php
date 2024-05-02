<?php

declare(strict_types=1);

namespace Bag\Casts;

use BackedEnum;
use Bag\Bag;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Support\Collection as LaravelCollection;
use function is_subclass_of;
use Override;
use UnitEnum;

class MagicCast implements CastsPropertySet
{
    #[Override]
    public function set(string $propertyType, string $propertyName, LaravelCollection $properties): mixed
    {
        $value = $properties->get($propertyName);

        return match (true) {
            $propertyType === 'int' => (int) $value,
            $propertyType === 'float' => (float) $value,
            $propertyType === 'bool' => (bool) $value,
            $propertyType === 'string' => (string) $value,
            is_string($value) && (
                is_a($propertyType, \DateTime::class, true) ||
                is_a($propertyType, DateTimeImmutable::class, true) ||
                is_a($propertyType, Carbon::class, true) ||
                is_a($propertyType, CarbonImmutable::class, true)
            ) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')),
            is_subclass_of($propertyType, Bag::class, true) => $propertyType::from($value),
            is_a($propertyType, LaravelCollection::class, true) || is_subclass_of($propertyType, LaravelCollection::class, true) => $propertyType::make($value),
            is_subclass_of($propertyType, BackedEnum::class, true) => $propertyType::from($value),
            is_subclass_of($propertyType, UnitEnum::class, true) => constant("{$propertyType}::{$value}"),
            default => $value,
        };
    }
}
