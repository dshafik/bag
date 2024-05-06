<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Hidden;
use Bag\Bag;

readonly class HiddenParametersBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
        #[Hidden]
        public string $email
    ) {
    }
}
