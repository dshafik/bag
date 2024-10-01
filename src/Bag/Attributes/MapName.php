<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;
use Bag\Mappers\MapperInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class MapName implements AttributeInterface
{
    /**
     * @param class-string<MapperInterface>|null $input
     * @param class-string<MapperInterface>|null $output
     * @param array<array-key,mixed> $inputParams
     * @param array<array-key,mixed> $outputParams
     */
    public function __construct(public ?string $input = null, public ?string $output = null, public array $inputParams = [], public array $outputParams = [])
    {
    }
}
