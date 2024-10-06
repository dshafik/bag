<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class In extends Rule implements AttributeInterface
{
    public function __construct(mixed ... $values)
    {
        parent::__construct('in', ... $values);
    }
}
