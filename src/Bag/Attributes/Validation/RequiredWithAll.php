<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class RequiredWithAll extends Rule
{
    public function __construct(string ...$otherFieldNames)
    {
        parent::__construct('required_with_all', ... $otherFieldNames);
    }
}
