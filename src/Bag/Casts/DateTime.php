<?php

declare(strict_types=1);

namespace Bag\Casts;

use Carbon\Exceptions\InvalidFormatException;
use DateMalformedStringException;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Override;

/**
 * @template T of DateTimeInterface
 */
readonly class DateTime implements CastsPropertyGet, CastsPropertySet
{
    /**
     * @param  class-string<T>|null  $dateTimeClass
     */
    public function __construct(protected string $format = 'Y-m-d H:i:s', protected ?string $outputFormat = null, protected ?string $dateTimeClass = null, protected bool $strictMode = false)
    {
    }

    #[Override]
    /**
     * @param Collection<array-key,T> $properties
     */
    public function get(string $propertyName, Collection $properties): mixed
    {
        /** @var T $dateTime */
        $dateTime = $properties->get($propertyName);

        return $dateTime->format($this->outputFormat ?? $this->format);
    }

    /**
     * @param class-string<T> $propertyType
     * @param Collection<array-key,mixed> $properties
     * @return T
     * @throws DateMalformedStringException
     */
    #[Override]
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        $dateTimeClass = $this->dateTimeClass;
        if ($dateTimeClass === null) {
            $dateTimeClass = $propertyType;
        }

        $value = $properties->get($propertyName);

        if ($value instanceof $dateTimeClass) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            // @phpstan-ignore staticMethod.notFound
            return $dateTimeClass::createFromFormat('U.u', $value->format('U.u'));
        }

        if ($this->strictMode) {
            // @phpstan-ignore staticMethod.notFound
            return $dateTimeClass::createFromFormat($this->format, $value);
        }

        try {
            // @phpstan-ignore staticMethod.notFound
            return $dateTimeClass::createFromFormat($this->format, $value);
        } catch (InvalidFormatException) {
            /** @var string $value */
            $datetime = new DateTimeImmutable($value);

            // @phpstan-ignore staticMethod.notFound
            return $dateTimeClass::createFromFormat('U.u', $datetime->format('U.u'));
        }
    }
}
