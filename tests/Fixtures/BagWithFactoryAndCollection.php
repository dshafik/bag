<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Collection;
use Bag\Attributes\Factory;
use Bag\Bag;
use Bag\Traits\HasFactory;
use Tests\Fixtures\Collections\BagWithFactoryAndCollectionCollection;
use Tests\Fixtures\Factories\BagWithFactoryFactory;

#[Factory(BagWithFactoryFactory::class)]
#[Collection(BagWithFactoryAndCollectionCollection::class)]
readonly class BagWithFactoryAndCollection extends Bag
{
    use HasFactory;

    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
