<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Transforms
{
    public Collection $types;

    public function __construct(string ...$types)
    {
        $this->types = collect($types);
    }
}
