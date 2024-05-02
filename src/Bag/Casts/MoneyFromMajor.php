<?php

declare(strict_types=1);

namespace Bag\Casts;

use Brick\Money\Money as BrickMoney;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;

class MoneyFromMajor extends MoneyFromMinor
{
    protected function makeMoney(mixed $amount, int|string|CurrencyAlpha3|null $currency): BrickMoney
    {
        return BrickMoney::of($amount, $currency);
    }
}
