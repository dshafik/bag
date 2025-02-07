<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class NullablePropertiesBag extends Bag
{
    public function __construct(
        public ?string $name,
        public ?int $age,
        public ?string $email,
        public ?TestBag $bag,
    ) {
    }
}
