<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Regex extends Rule
{
    public function __construct(string $regex)
    {
        parent::__construct('regex', $regex);
    }
}
