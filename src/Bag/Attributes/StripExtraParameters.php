<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_PARAMETER)]
class StripExtraParameters implements AttributeInterface
{
}
