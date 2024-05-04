<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Computed;
use Bag\Bag;

readonly class ComputedPropertyMissingBag extends Bag
{
    #[Computed]
    public int $dob;

    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
