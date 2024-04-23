<?php declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Collection;
use Bag\Bag;
use Tests\Fixtures\Collections\BagWithCollectionCollection;

#[Collection(BagWithCollectionCollection::class)]
readonly class BagWithCollection extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
