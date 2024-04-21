<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Size extends Rule
{
    public function __construct(int $size)
    {
        parent::__construct('size', $size);
    }
}
