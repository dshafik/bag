<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;

#[MapName(output: SnakeCase::class)]
readonly class MappedOutputNameClassBag extends Bag
{
    public function __construct(
        public string $nameGoesHere,
        public int $ageGoesHere,
        public string $emailGoesHere
    ) {
    }
}
