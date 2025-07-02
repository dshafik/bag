<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;
use Bag\Values\Optional;

readonly class OptionalValueBag extends Bag
{
    public function __construct(
        public Optional|string $name,
        public Optional|int $age,
    ) {
    }
}
