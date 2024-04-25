<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Collection
{
    /**
     * @param class-string<\Illuminate\Support\Collection> $collectionClass
     */
    public function __construct(
        public string $collectionClass
    ) {
    }
}
