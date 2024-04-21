<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\SnakeCaseMapper;

#[MapName(output: SnakeCaseMapper::class)]
readonly class MappedOutputNameClassBag extends Bag
{
    public function __construct(
        public string $nameGoesHere,
        public int $ageGoesHere,
        public string $emailGoesHere
    ) {
    }
}
