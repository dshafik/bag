<?php

declare(strict_types=1);

namespace Bag\Casts;

use Illuminate\Support\Collection;

interface CastsPropertySet
{
    /**
     * @param Collection<array-key,mixed> $properties
     */
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed;
}
