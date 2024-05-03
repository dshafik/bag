<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;
use Bag\Traits\HasFactory;

readonly class BagWithoutFactoryAttribute extends Bag
{
    use HasFactory;
}
