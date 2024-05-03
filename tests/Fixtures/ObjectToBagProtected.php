<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Bag;
use Bag\Traits\HasBag;
use Tests\Fixtures\Values\OptionalPropertiesBag;

#[Bag(OptionalPropertiesBag::class, Bag::PUBLIC | Bag::PROTECTED)]
class ObjectToBagProtected
{
    use HasBag;

    public function __construct(
        public string $name,
        protected int $age,
        private string $email,
    ) {
    }
}
