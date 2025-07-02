<?php

declare(strict_types=1);

namespace Bag\Casts;

use BackedEnum;
use Bag\Bag;
use Bag\Collection;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection as LaravelCollection;
use function is_subclass_of;
use Override;
use UnitEnum;

class MagicCast implements CastsPropertySet
{
    /**
     * @param Collection<string> $propertyTypes
     */
    #[Override]
    public function set(Collection $propertyTypes, string $propertyName, LaravelCollection $properties): mixed
    {
        $value = $properties->get($propertyName);

        // Find the correct type for the property
        $valueType = get_debug_type($value);

        // Exact Match to a type
        if ($propertyTypes->filter(function ($type) use ($valueType, $value) {
            /** @var string|class-string $type */
            return $type === $valueType || ((is_object($value) || is_string($value)) && is_a($value, $type, true));
        })->isNotEmpty()) {
            return $value;
        }

        // Fuzzy Matches
        /** @var Collection<string> $propertyType */
        $propertyType = $propertyTypes->first(function ($type) {
            // @phpstan-ignore cast.string
            $type = (string) $type;

            return match (true) {
                is_a($type, \DateTime::class, true) => true,
                is_a($type, DateTimeImmutable::class, true) => true,
                is_a($type, Carbon::class, true) => true,
                is_a($type, CarbonImmutable::class, true) => true,
                is_a($type, Bag::class, true) => true,
                (is_a($type, LaravelCollection::class, true) || is_subclass_of((string) $type, LaravelCollection::class)) => true,
                is_a($type, BackedEnum::class, true) => true,
                is_a($type, UnitEnum::class, true) => true,
                is_subclass_of($type, Model::class) => true,
                $type === 'float' => true,
                $type === 'int' => true,
                $type === 'string' => true,
                $type === 'bool' => true,
                default => false,
            };
        });

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
                // @phpstan-ignore cast.string
                is_a((string) $propertyType, Carbon::class, true) ||
                // @phpstan-ignore cast.string
                is_a((string) $propertyType, CarbonImmutable::class, true)
            ) => $propertyType::createFromFormat('U.u', (new DateTimeImmutable($value))->format('U.u')), // @phpstan-ignore staticMethod.notFound
            is_subclass_of($propertyType, Bag::class, true) => $propertyType::from($value),
            (is_a($propertyType, LaravelCollection::class, true) || is_subclass_of((string) $propertyType, LaravelCollection::class)) && \is_iterable($value) => $propertyType::make($value),
            is_subclass_of($propertyType, BackedEnum::class, true) && (is_string($value) || is_int($value)) => $propertyType::from($value),
            is_subclass_of($propertyType, UnitEnum::class, true) && is_string($value) => constant("{$propertyType}::{$value}"),
            is_subclass_of($propertyType, Model::class) && \is_scalar($value) => $propertyType::findOrFail($value),
            default => $value,
        };
    }
}
