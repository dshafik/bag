<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Min extends Rule
{
    public function __construct(int $minimum)
    {
        parent::__construct('min', $minimum);
    }
}
