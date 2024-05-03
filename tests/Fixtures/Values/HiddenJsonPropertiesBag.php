<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Hidden;
use Bag\Attributes\HiddenFromJson;
use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;

#[MapName(output: SnakeCase::class)]
readonly class HiddenJsonPropertiesBag extends Bag
{
    public function __construct(
        public string $nameGoesHere,
        public int $ageGoesHere,
        #[HiddenFromJson]
        public string $emailGoesHere,
        #[Hidden]
        public string $passwordGoesHere
    ) {
    }
}
