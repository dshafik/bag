<?php

declare(strict_types=1);

namespace Bag\Casts;

use Bag\Collection;
use Carbon\Exceptions\InvalidFormatException;
use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection as LaravelCollection;
use Override;
use ReflectionNamedType;

/**
 * @template T of DateTimeInterface
 */
class DateTime implements CastsPropertyGet, CastsPropertySet
{
    /**
     * @param  class-string<T>|null  $dateTimeClass
     */
    public function __construct(protected string $format = 'Y-m-d H:i:s', protected ?string $outputFormat = null, protected ?string $dateTimeClass = null, protected bool $strictMode = false)
    {
    }

    #[Override]
    /**
     * @param LaravelCollection<array-key,T> $properties
     */
    public function get(string $propertyName, LaravelCollection $properties): mixed
    {
        /** @var T|null $dateTime */
        $dateTime = $properties->get($propertyName);

        return $dateTime?->format($this->outputFormat ?? $this->format);
    }

    /**
     * @param Collection<ReflectionNamedType> $propertyTypes
     * @param LaravelCollection<array-key,mixed> $properties
     * @return T|null
     * @throws DateMalformedStringException
     */
    #[Override]
    public function set(Collection $propertyTypes, string $propertyName, LaravelCollection $properties): mixed
    {
        $value = $properties->get($propertyName);

        if ($propertyTypes->contains('null') && $value === null) {
            return null;
        }

        if ($this->dateTimeClass === null) {
            /** @var class-string<T> $type */
            $type = $propertyTypes->first();
            $this->dateTimeClass = $type;
        }

        if ($value instanceof $this->dateTimeClass) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            // @phpstan-ignore staticMethod.notFound
            return $this->dateTimeClass::createFromFormat('U.u', $value->format('U.u'));
        }

        if ($this->strictMode) {
            // @phpstan-ignore staticMethod.notFound
            return $this->dateTimeClass::createFromFormat($this->format, $value);
        }

        try {
            // @phpstan-ignore staticMethod.notFound
            return $this->dateTimeClass::createFromFormat($this->format, $value);
        } catch (InvalidFormatException) {
            /** @var string $value */
            $datetime = new DateTimeImmutable($value);

            // @phpstan-ignore staticMethod.notFound
            return $this->dateTimeClass::createFromFormat('U.u', $datetime->format('U.u'));
        }
    }
}
