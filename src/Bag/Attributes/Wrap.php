<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class Wrap implements AttributeInterface
{
    public function __construct(
        public string $wrapKey,
    ) {
    }
}
