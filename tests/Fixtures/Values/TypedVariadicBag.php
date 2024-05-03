<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class TypedVariadicBag extends Bag
{
    public array $values;

    public function __construct(
        public string $name,
        public int $age,
        bool ...$values
    ) {
        $this->values = $values;
    }
}
