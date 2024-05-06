<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Computed;
use Bag\Attributes\Hidden;
use Bag\Attributes\HiddenFromJson;
use Bag\Bag;
use Carbon\CarbonImmutable;

readonly class ComputedPropertyHiddenBag extends Bag
{
    #[Computed]
    #[Hidden]
    #[HiddenFromJson]
    public CarbonImmutable $dob;

    public function __construct(
        public string $name,
        public int $age,
    ) {
        $this->dob = CarbonImmutable::now()->subYears($this->age);
    }
}
