<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\CamelCase;
use Bag\Mappers\SnakeCase;

readonly class MappedPropertyBag extends Bag
{
    public function __construct(
        #[MapName(input: SnakeCase::class, output: SnakeCase::class)]
        public string $nameGoesHere,
        #[MapName(input: SnakeCase::class, output: SnakeCase::class)]
        public int $ageGoesHere,
        #[MapName(input: CamelCase::class, output: CamelCase::class)]
        public string $email_goes_here,
    ) {
    }
}
