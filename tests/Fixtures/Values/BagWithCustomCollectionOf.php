<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Cast;
use Bag\Bag;
use Bag\Casts\CollectionOf;
use Tests\Fixtures\Collections\BagWithCollectionCollection;

readonly class BagWithCustomCollectionOf extends Bag
{
    public function __construct(
        #[Cast(CollectionOf::class, TestBag::class)]
        public BagWithCollectionCollection $bags,
    ) {
    }
}
