<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Property\ValueCollection;

trait WithVariadics
{
    protected static function isVariadic(ValueCollection $properties): bool
    {
        return $properties->last()->variadic;
    }
}
