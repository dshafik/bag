<?php

declare(strict_types=1);

namespace Tests\Fixtures\Casts;

use Bag\Casts\CastsPropertySet;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CastInputOnly implements CastsPropertySet
{
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        return Str::of($properties->get($propertyName))->upper();
    }
}
