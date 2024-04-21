<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Between extends Rule
{
    public function __construct(int $min, int $max)
    {
        parent::__construct('between', $min, $max);
    }
}
