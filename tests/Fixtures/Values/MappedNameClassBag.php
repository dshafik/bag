<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;

#[MapName(input: SnakeCase::class, output: SnakeCase::class)]
readonly class MappedNameClassBag extends Bag
{
    public function __construct(
        public string $nameGoesHere,
        public int $ageGoesHere,
        public string $emailGoesHere
    ) {
    }
}
