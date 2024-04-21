<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Bag;

readonly class NoPropertiesBag extends Bag
{
    public function __construct()
    {
    }
}
