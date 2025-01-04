<?php

declare(strict_types=1);

namespace Tests\Fixtures\Casts;

use Bag\Casts\CastsPropertySet;
use Bag\Collection;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Support\Str;

class CastInputOnly implements CastsPropertySet
{
    public function set(Collection $propertyTypes, string $propertyName, LaravelCollection $properties): mixed
    {
        return Str::of($properties->get($propertyName))->upper();
    }
}
