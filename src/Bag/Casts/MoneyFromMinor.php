<?php

declare(strict_types=1);

namespace Bag\Casts;

use BackedEnum;
use Bag\Collection;
use Brick\Math\BigNumber;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money as BrickMoney;
use Illuminate\Support\Collection as LaravelCollection;
use Override;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use UnitEnum;

class MoneyFromMinor implements CastsPropertySet, CastsPropertyGet
{
    public function __construct(protected CurrencyAlpha3|string|null $currency = null, protected ?string $currencyProperty = null, protected string $locale = 'en_US')
    {
    }

    #[Override]
    public function set(Collection $propertyTypes, string $propertyName, LaravelCollection $properties): mixed
    {
        /** @var BigNumber|float|int|string $amount */
        $amount = $properties->get($propertyName);

        if ($amount instanceof BrickMoney) {
            return $amount;
        }

        $currency = $this->currency;
        if ($this->currencyProperty !== null) {
            $currency = $properties->get($this->currencyProperty, $this->currency);
        }

        if ($currency === null) {
            throw new UnknownCurrencyException('No currency found');
        }

        if ($currency instanceof BackedEnum) {
            $currency = $currency->value;
        }

        if ($currency instanceof UnitEnum) {
            $currency = $currency->name;
        }

        /** @var int|string $currency */
        return $this->makeMoney($amount, $currency);
    }

    #[Override]
    public function get(string $propertyName, LaravelCollection $properties): mixed
    {
        /** @var BrickMoney $money */
        $money = $properties->get($propertyName);

        return $money->formatTo($this->locale);
    }

    /**
     * @param BigNumber|float|int|string $amount
     */
    protected function makeMoney(mixed $amount, int|string $currency): BrickMoney
    {
        return BrickMoney::ofMinor($amount, $currency);
    }
}
