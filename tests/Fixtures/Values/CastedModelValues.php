<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;

#[MapName(SnakeCase::class, SnakeCase::class)]
readonly class CastedModelValues extends Bag
{
    public function __construct(
        public ?string $name = null,
        public ?int $age = null,
        public ?string $email = null,
        public ?TestBag $bag = null,
        public ?NullableWithDefaultValueBag $nullsBag = null,
    ) {
    }
}
