<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class BagWithSingleArrayParameter extends Bag
{
    public function __construct(
        public array $items
    ) {
    }
}
