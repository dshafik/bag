<?php

declare(strict_types=1);

namespace Bag\Casts;

use Illuminate\Support\Collection;

interface CastsPropertyGet
{
    /**
     * @param Collection<array-key,mixed> $properties
     */
    public function get(string $propertyName, Collection $properties): mixed;
}
