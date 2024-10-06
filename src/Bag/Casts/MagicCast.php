<?php

declare(strict_types=1);

namespace Bag\Casts;

use BackedEnum;
use Bag\Bag;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as LaravelCollection;
use function is_subclass_of;
use Override;
use UnitEnum;

readonly class MagicCast implements CastsPropertySet
{
    #[Override]
    public function set(string $propertyType, string $propertyName, LaravelCollection $properties): mixed
    {
        $value = $properties->get($propertyName);

        return match (true) {
            $value === null => null,
            // @phpstan-ignore cast.int
            $propertyType === 'int' => (int) $value,
            // @phpstan-ignore cast.double
            $propertyType === 'float' => (float) $value,
            $propertyType === 'bool' => (bool) $value,
            // @phpstan-ignore cast.string
            $propertyType === 'string' => (string) $value,
            is_object($value) && $propertyType === $value::class => $value,
            is_string($value) && (
                is_a($propertyType, \DateTime::class, true) ||
                is_a($propertyType, DateTimeImmutable::class, true) ||
                is_a($propertyType, Carbon::class, true) ||
                is_a($propertyType, CarbonImmutable::class, true)
            ) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')),
            is_subclass_of($propertyType, Bag::class, true) => $propertyType::from($value),
            // @phpstan-ignore argument.templateType
            (is_a($propertyType, LaravelCollection::class, true) || is_subclass_of($propertyType, LaravelCollection::class, true)) && \is_iterable($value) => $propertyType::make($value),
            is_subclass_of($propertyType, BackedEnum::class, true) && (is_string($value) || is_int($value)) => $propertyType::from($value),
            is_subclass_of($propertyType, UnitEnum::class, true) && is_string($value) => constant("{$propertyType}::{$value}"),
            is_subclass_of($propertyType, Model::class) && \is_scalar($value) => $propertyType::findOrFail($value),
            default => $value,
        };
    }
}
