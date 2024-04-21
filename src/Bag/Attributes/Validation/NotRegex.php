<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class NotRegex extends Rule
{
    public function __construct(string $regex)
    {
        parent::__construct('not_regex', $regex);
    }
}
