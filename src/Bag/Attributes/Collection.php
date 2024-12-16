<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class Collection implements AttributeInterface
{
    /**
     * @param class-string<\Illuminate\Support\Collection<array-key,mixed>> $collectionClass
     */
    public function __construct(
        public string $collectionClass
    ) {
    }
}
