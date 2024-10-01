<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_CLASS)]
class Factory implements AttributeInterface
{
    /**
     * @param class-string<Factory> $factoryClass
     */
    public function __construct(public string $factoryClass)
    {
    }
}
