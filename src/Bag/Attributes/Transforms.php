<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
readonly class Transforms implements AttributeInterface
{
    /**
     * @var Collection<array-key, string>
     */
    public Collection $types;

    public function __construct(string ...$types)
    {
        $this->types = collect($types);
    }
}
