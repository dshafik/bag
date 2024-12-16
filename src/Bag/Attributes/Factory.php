<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;
use Bag\Bag;
use Bag\Factory as BagFactory;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class Factory implements AttributeInterface
{
    /**
     * @template T of Bag
     * @param class-string<BagFactory<T>> $factoryClass
     */
    public function __construct(public string $factoryClass)
    {
    }
}
