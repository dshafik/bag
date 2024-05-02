<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Bag;
use Bag\Traits\HasFactory;

readonly class BagWithoutFactoryAttribute extends Bag
{
    use HasFactory;
}
