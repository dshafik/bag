<?php

declare(strict_types=1);

namespace Bag\Casts;

use BackedEnum;
use Bag\Bag;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Support\Carbon as IlluminateCarbon;
use Illuminate\Support\Collection;
use function is_subclass_of;
use Override;
use UnitEnum;

class MagicCast implements CastsPropertySet
{
    #[Override]
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        /** @var mixed $value */
        $value = $properties->get($propertyName);

        return match (true) {
            $propertyType === 'int' => (int) $value,
            $propertyType === 'float' => (float) $value,
            $propertyType === 'bool' => (bool) $value,
            $propertyType === 'string' => (string) $value,
            is_string($value) && is_subclass_of($propertyType, \DateTime::class, true) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')),
            is_string($value) && is_subclass_of($propertyType, DateTimeImmutable::class, true) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')),
            is_string($value) && is_subclass_of($propertyType, Carbon::class, true) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')),
            is_string($value) && is_subclass_of($propertyType, CarbonImmutable::class, true) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')),
            is_string($value) && is_subclass_of($propertyType, IlluminateCarbon::class, true) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')),
            is_subclass_of($propertyType, Bag::class, true) => $propertyType::from($value),
            is_subclass_of($propertyType, Collection::class, true) => $propertyType::make($value),
            is_subclass_of($propertyType, BackedEnum::class, true) => $propertyType::from($value),
            is_subclass_of($propertyType, UnitEnum::class, true) => constant("{$propertyType}::{$value}"),
            default => $value,
        };
    }
}
