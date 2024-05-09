<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\MapInputName;
use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;
use Bag\Mappers\Stringable;

#[MapName(input: Stringable::class, output: SnakeCase::class, inputParams: ['upper'])]
#[MapInputName(SnakeCase::class)]
readonly class MappedNameClassBag extends Bag
{
    public function __construct(
        public string $nameGoesHere,
        public int $ageGoesHere,
        public string $emailGoesHere
    ) {
    }
}
