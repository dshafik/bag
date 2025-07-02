<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class NullableWithDefaultValueBag extends Bag
{
    public function __construct(
        public ?string $name = null,
        public ?int $age = null,
        public ?string $email = null,
        public ?TestBag $bag = null,
    ) {
    }
}
