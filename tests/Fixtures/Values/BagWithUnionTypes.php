<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;
use Tests\Fixtures\Enums\TestBackedEnum;

readonly class BagWithUnionTypes extends Bag
{
    public function __construct(
        public string|TestBackedEnum $name,
        public string|int $age,
        public string|bool $email,
    ) {
    }
}
