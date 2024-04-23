<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Factory
{
    /**
     * @param class-string<Factory> $factoryClass
     */
    public function __construct(public string $factoryClass)
    {
    }
}
