<?php

declare(strict_types=1);

namespace Bag\Casts;

use Illuminate\Support\Collection;

interface CastsPropertySet
{
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed;
}
