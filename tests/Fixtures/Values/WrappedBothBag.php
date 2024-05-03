<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Wrap;
use Bag\Attributes\WrapJson;
use Bag\Bag;

#[Wrap('wrapper')]
#[WrapJson('json_wrapper')]
readonly class WrappedBothBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
