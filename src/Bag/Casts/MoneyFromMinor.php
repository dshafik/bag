<?php

declare(strict_types=1);

namespace Bag\Casts;

use BackedEnum;
use Brick\Money\Money;
use Illuminate\Support\Collection;
use Override;
use UnitEnum;

class MoneyFromMinor implements CastsPropertySet, CastsPropertyGet
{
    public function __construct(protected string $currencyProperty = 'currency', protected string $locale = 'en_US')
    {
    }

    #[Override]
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        $amount = $properties->first($propertyName);

        if ($amount instanceof Money) {
            return $amount;
        }

        $currency = $properties->get($this->currencyProperty);

        if ($currency instanceof BackedEnum) {
            $currency = $currency->value;
        }

        if ($currency instanceof UnitEnum) {
            $currency = $currency->name;
        }

        return Money::ofMinor($amount, $currency);
    }

    #[Override]
    public function get(string $propertyName, Collection $properties): mixed
    {
        /** @var Money $money */
        $money = $properties->get($propertyName);
        return $money->formatTo($this->locale);
    }
}
