<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequiredWith extends Rule
{
    public function __construct(string $otherFieldName)
    {
        parent::__construct('required_with', $otherFieldName);
    }
}
