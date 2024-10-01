<?php

declare(strict_types=1);

namespace Bag\Attributes\Laravel;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class FromRouteParameter implements AttributeInterface
{
    public function __construct(
        public ?string $parameterName = null,
    ) {
    }
}
