<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;
use Carbon\CarbonImmutable;

readonly class CastsMagicDateBag extends Bag
{
    public function __construct(
        public CarbonImmutable $date,
    ) {
    }
}
