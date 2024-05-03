<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Cast;
use Bag\Bag;
use Illuminate\Support\Stringable;
use Tests\Fixtures\Casts\CastInputOnly;
use Tests\Fixtures\Casts\CastOutputOnly;

readonly class CastInputOutputBag extends Bag
{
    public function __construct(
        #[Cast(CastInputOnly::class)]
        public Stringable $input,
        #[Cast(CastOutputOnly::class)]
        public string $output,
    ) {
    }
}
