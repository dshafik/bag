<?php

declare(strict_types=1);

namespace Tests\Fixtures\Casts;

use Bag\Casts\CastsPropertyGet;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CastOutputOnly implements CastsPropertyGet
{
    public function get(string $propertyName, Collection $properties): mixed
    {
        return Str::of($properties->get($propertyName))->upper()->toString();
    }
}
