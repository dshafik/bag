<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Computed;
use Bag\Bag;
use Carbon\CarbonImmutable;

readonly class ComputedPropertyBag extends Bag
{
    #[Computed]
    public CarbonImmutable $dob;

    public function __construct(
        public string $name,
        public int $age,
    ) {
        $this->dob = CarbonImmutable::now()->subYears($this->age);
    }
}
