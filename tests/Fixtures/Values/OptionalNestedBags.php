<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;
use Bag\Values\Optional;

readonly class OptionalNestedBags extends Bag
{
    public function __construct(
        public Optional|TestBag $test,
        public Optional|BagWithSingleArrayParameter $bagWithSingleArrayParameter,
    ) {
    }
}
