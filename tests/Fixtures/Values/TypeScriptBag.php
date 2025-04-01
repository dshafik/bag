<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\MapOutputName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;
use Bag\Values\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapOutputName(SnakeCase::class)]
readonly class TypeScriptBag extends Bag
{
    public function __construct(
        public string $name,
        public Optional|int $age,
        public Optional|string|null $emailAddress = null,
    ) {
    }
}
