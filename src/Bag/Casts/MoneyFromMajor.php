<?php

declare(strict_types=1);

namespace Bag\Casts;

use Brick\Math\BigNumber;
use Brick\Money\Money as BrickMoney;

readonly class MoneyFromMajor extends MoneyFromMinor
{
    /**
     * @param BigNumber|float|int|string $amount
     */
    protected function makeMoney(mixed $amount, int|string $currency): BrickMoney
    {
        return BrickMoney::of($amount, $currency);
    }
}
