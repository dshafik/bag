<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Bag;

readonly class VariadicBag extends Bag
{
    public array $extra;

    public function __construct(
        public string $name,
        public int $age,
        mixed ...$extra
    ) {
        $this->extra = $extra;
    }
}
