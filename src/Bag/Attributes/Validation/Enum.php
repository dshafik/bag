<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Enum extends Rule
{
    /**
     * @type class-string<BackedEnum>
     */
    public function __construct(string $enumName)
    {
        parent::__construct(\Illuminate\Validation\Rules\Enum::class, $enumName);
    }
}
