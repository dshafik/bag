<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;
use BackedEnum;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class Enum extends Rule implements AttributeInterface
{
    /**
     * @type class-string<BackedEnum>
     */
    public function __construct(string $enumName)
    {
        parent::__construct(\Illuminate\Validation\Rules\Enum::class, $enumName);
    }
}
