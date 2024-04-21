<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Validation\Integer;
use Bag\Attributes\Validation\Required;
use Bag\Attributes\Validation\Str;
use Bag\Bag;

readonly class ValidateUsingAttributesBag extends Bag
{
    public function __construct(
        #[Required]
        #[Str]
        public string $name,
        #[Required]
        #[Integer]
        public int $age,
    ) {
    }
}
