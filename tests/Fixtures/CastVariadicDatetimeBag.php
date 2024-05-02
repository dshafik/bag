<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Cast;
use Bag\Bag;
use Bag\Casts\DateTime;
use Carbon\CarbonImmutable;

readonly class CastVariadicDatetimeBag extends Bag
{
    public array $values;

    public function __construct(
        public string $name,
        public int $age,
        #[Cast(DateTime::class, format: 'Y-m-d')]
        CarbonImmutable ...$values
    ) {
        $this->values = $values;
    }
}
