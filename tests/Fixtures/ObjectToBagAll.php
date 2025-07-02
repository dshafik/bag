<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Bag;
use Bag\Traits\HasBag;
use Tests\Fixtures\Values\NullableWithDefaultValueBag;

#[Bag(NullableWithDefaultValueBag::class, Bag::PUBLIC | Bag::PROTECTED | Bag::PRIVATE)]
class ObjectToBagAll
{
    use HasBag;

    public function __construct(
        public string $name,
        protected int $age,
        private string $email,
    ) {
    }
}
