<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class WrapJson
{
    public function __construct(
        public string $wrapKey,
    ) {
    }
}
