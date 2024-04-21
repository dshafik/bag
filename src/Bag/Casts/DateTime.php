<?php

declare(strict_types=1);

namespace Bag\Casts;

use Carbon\Exceptions\InvalidFormatException;
use DateTimeImmutable;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Override;

class DateTime implements CastsPropertyGet, CastsPropertySet
{
    /**
     * @param  class-string<DateTimeInterface>|null  $dateTimeClass
     */
    public function __construct(protected string $format = 'Y-m-d H:i:s', protected ?string $outputFormat = null, protected ?string $dateTimeClass = null, protected bool $strictMode = false)
    {
        if ($this->outputFormat === null) {
            $this->outputFormat = $this->format;
        }
    }

    #[Override]
    public function get(string $propertyName, Collection $properties): mixed
    {
        return $properties->get($propertyName)->format($this->outputFormat);
    }

    #[Override]
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        if ($this->dateTimeClass === null) {
            $this->dateTimeClass = $propertyType;
        }

        $value = $properties->get($propertyName);

        if ($value instanceof DateTimeInterface) {
            return $value;
        }

        if ($this->strictMode) {
            return $this->dateTimeClass::createFromFormat($this->format, $value);
        }

        try {
            return $this->dateTimeClass::createFromFormat($this->format, $value);
        } catch (InvalidFormatException) {
            $datetime = new DateTimeImmutable($value);

            return $this->dateTimeClass::createFromFormat('U.u', $datetime->format('U.u'));
        }
    }
}
