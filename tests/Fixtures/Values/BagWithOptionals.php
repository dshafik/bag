<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;
use Bag\Values\Optional;

readonly class BagWithOptionals extends Bag
{
    public function __construct(
        public string $name,
        public Optional|int $age,
        public Optional|string|null $email,
    ) {
    }
}
