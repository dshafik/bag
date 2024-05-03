<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\CastInput;
use Bag\Bag;
use Bag\Casts\DateTime;
use Carbon\CarbonImmutable;

readonly class CastsDateInputBag extends Bag
{
    public function __construct(
        #[CastInput(DateTime::class, format: 'Y-m-d')]
        public CarbonImmutable $date,
    ) {
    }
}
