<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequiredIf extends Rule implements AttributeInterface
{
    public function __construct(string $otherFieldName, mixed $value)
    {
        parent::__construct('required_if', $otherFieldName, $value);
    }
}
