<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class In extends Rule
{
    public function __construct(mixed ... $values)
    {
        parent::__construct('in', ... $values);
    }
}
