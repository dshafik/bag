<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Bag;
use Bag\Traits\HasBag;

#[Bag(OptionalPropertiesBag::class, Bag::PRIVATE)]
class ObjectToBagPrivate
{
    use HasBag;

    public function __construct(
        public string $name,
        protected int $age,
        private string $email,
    ) {
    }
}
