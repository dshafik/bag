<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Regex extends Rule implements AttributeInterface
{
    public function __construct(string $regex)
    {
        parent::__construct('regex', $regex);
    }
}
