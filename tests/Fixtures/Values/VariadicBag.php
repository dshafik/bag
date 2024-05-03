<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class VariadicBag extends Bag
{
    public array $values;

    public function __construct(
        public string $name,
        public int $age,
        mixed ...$values
    ) {
        $this->values = $values;
    }
}
