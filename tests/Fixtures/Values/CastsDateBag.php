<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Cast;
use Bag\Bag;
use Bag\Casts\DateTime;
use Carbon\CarbonImmutable;

readonly class CastsDateBag extends Bag
{
    public function __construct(
        #[Cast(DateTime::class, format: 'Y-m-d')]
        public CarbonImmutable $date,
    ) {
    }
}
