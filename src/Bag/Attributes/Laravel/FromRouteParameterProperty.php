<?php

declare(strict_types=1);

namespace Bag\Attributes\Laravel;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER)]
class FromRouteParameterProperty
{
    public function __construct(
        public string $parameterName,
        public ?string $propertyName = null,
    ) {
    }
}
