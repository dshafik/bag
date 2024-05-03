<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Bag;
use Bag\Traits\HasBag;

#[Bag('InvalidBagName')]
class ObjectToBagInvalidBag
{
    use HasBag;
}
