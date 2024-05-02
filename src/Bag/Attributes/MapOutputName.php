<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Mappers\MapperInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class MapOutputName extends MapName
{
    /**
     * @param  class-string<MapperInterface>  $mapper
     */
    public function __construct(public string $mapper, mixed ... $params)
    {
        parent::__construct(output: $mapper, outputParams: $params);
    }
}
