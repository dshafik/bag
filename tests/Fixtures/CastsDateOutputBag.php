<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\CastOutput;
use Bag\Bag;
use Bag\Casts\DateTime;
use Carbon\CarbonImmutable;

readonly class CastsDateOutputBag extends Bag
{
    public function __construct(
        #[CastOutput(DateTime::class, format: 'Y-m-d')]
        public CarbonImmutable $date,
    ) {
    }
}
