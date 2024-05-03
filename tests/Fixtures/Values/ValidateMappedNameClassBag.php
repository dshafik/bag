<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\MapName;
use Bag\Attributes\Validation\Integer;
use Bag\Attributes\Validation\Required;
use Bag\Attributes\Validation\Str;
use Bag\Bag;
use Bag\Mappers\SnakeCase;

#[MapName(input: SnakeCase::class, output: SnakeCase::class)]
readonly class ValidateMappedNameClassBag extends Bag
{
    public function __construct(
        #[Str]
        #[Required]
        public string $nameGoesHere,
        #[Integer]
        #[Required]
        public int $ageGoesHere,
    ) {
    }
}
