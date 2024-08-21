<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Factory;
use Bag\Bag;
use Bag\Traits\HasFactory;

#[Factory('BagWithMissingFactoryFactory')]
readonly class BagWithMissingFactory extends Bag
{
    use HasFactory;

    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
