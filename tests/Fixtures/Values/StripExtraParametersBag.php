<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\StripExtraParameters;
use Bag\Bag;

#[StripExtraParameters]
readonly class StripExtraParametersBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
        public string $email
    ) {
    }
}
