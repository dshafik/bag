<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\CamelCase;
use Bag\Mappers\SnakeCase;

#[MapName(input: SnakeCase::class, output: SnakeCase::class)]
readonly class MappedClassAndPropertyBag extends Bag
{
    public function __construct(
        public string $nameGoesHere,
        public int $ageGoesHere,
        #[MapName(input: CamelCase::class, output: CamelCase::class)]
        public string $email_goes_here,
    ) {
    }
}
