<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\SnakeCaseMapper;

#[MapName(input: SnakeCaseMapper::class)]
readonly class MappedInputNameClassBag extends Bag
{
    public function __construct(
        public string $nameGoesHere,
        public int $ageGoesHere,
        public string $emailGoesHere
    ) {
    }
}
