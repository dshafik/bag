<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Factory;
use Bag\Bag;
use Bag\Traits\HasFactory;

#[Factory('InvalidFactoryClass')]
readonly class BagWithInvalidFactoryAttribute extends Bag
{
    use HasFactory;
}
