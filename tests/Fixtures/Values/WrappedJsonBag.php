<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\WrapJson;
use Bag\Bag;

#[WrapJson('wrapper')]
readonly class WrappedJsonBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
