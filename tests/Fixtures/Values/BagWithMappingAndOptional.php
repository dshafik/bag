<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\MapInputName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;
use Bag\Values\Optional;

#[MapInputName(SnakeCase::class)]
readonly class BagWithMappingAndOptional extends Bag
{
    public function __construct(
        public string $name,
        public Optional|int $currentAge,
    ) {
    }
}
