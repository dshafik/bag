<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Wrap;
use Bag\Bag;

#[Wrap('wrapper')]
readonly class WrappedBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
