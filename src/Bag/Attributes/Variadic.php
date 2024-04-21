<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Variadic extends Cast
{
}
