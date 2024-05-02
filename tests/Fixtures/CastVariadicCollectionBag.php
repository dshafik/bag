<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Cast;
use Bag\Bag;
use Bag\Casts\CollectionOf;
use Tests\Fixtures\Collections\BagWithCollectionCollection;

readonly class CastVariadicCollectionBag extends Bag
{
    public array $values;

    public function __construct(
        public string $name,
        public int $age,
        #[Cast(CollectionOf::class, BagWithCollection::class)]
        BagWithCollectionCollection ...$values
    ) {
        $this->values = $values;
    }
}
