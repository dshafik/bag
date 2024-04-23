<?php declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Factory;
use Bag\Bag;
use Bag\Traits\HasFactory;
use Tests\Fixtures\Factories\BagWithFactoryFactory;

#[Factory(BagWithFactoryFactory::class)]
readonly class BagWithFactory extends Bag
{
    use HasFactory;

    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
