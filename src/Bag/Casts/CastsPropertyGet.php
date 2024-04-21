<?php

declare(strict_types=1);

namespace Bag\Casts;

use Illuminate\Support\Collection;

interface CastsPropertyGet
{
    public function get(string $propertyName, Collection $properties): mixed;
}
