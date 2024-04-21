<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Bag;

readonly class TestBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
        public string $email
    ) {
    }
}
