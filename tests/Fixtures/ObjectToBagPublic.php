<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Bag;
use Bag\Traits\HasBag;
use Tests\Fixtures\Values\NullableWithDefaultValueBag;

#[Bag(NullableWithDefaultValueBag::class)]
class ObjectToBagPublic
{
    use HasBag;

    public function __construct(
        public string $name,
        protected int $age,
        private string $email,
    ) {
    }
}
