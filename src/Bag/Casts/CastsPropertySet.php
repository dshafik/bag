<?php

declare(strict_types=1);

namespace Bag\Casts;

use Bag\Collection;
use Illuminate\Support\Collection as LaravelCollection;

interface CastsPropertySet
{
    /**
     * @param LaravelCollection<array-key,mixed> $properties
     */
    public function set(Collection $propertyTypes, string $propertyName, LaravelCollection $properties): mixed;
}
