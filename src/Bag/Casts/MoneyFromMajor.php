<?php

declare(strict_types=1);

namespace Bag\Casts;

use Brick\Money\Money as BrickMoney;

class MoneyFromMajor extends MoneyFromMinor
{
    protected function makeMoney(mixed $amount, int|string $currency): BrickMoney
    {
        return BrickMoney::of($amount, $currency);
    }
}
