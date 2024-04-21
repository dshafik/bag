<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Mappers\MapperInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class MapName
{
    /**
     * @param  class-string<MapperInterface>|null  $input
     * @param  class-string<MapperInterface>|null  $output
     */
    public function __construct(public ?string $input = null, public ?string $output = null)
    {

    }
}
