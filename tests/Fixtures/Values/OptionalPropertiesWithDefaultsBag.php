<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class OptionalPropertiesWithDefaultsBag extends Bag
{
    public function __construct(
        public ?string $name = 'Davey Shafik',
        public ?int $age = 40,
        public ?string $email = 'davey@php.net',
        public ?TestBag $bag = null,
    ) {
    }
}
