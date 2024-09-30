<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Transforms;
use Bag\Bag;

readonly class BagWithArrayTransformer extends Bag
{
    public function __construct(
        public array $values,
    ) {
    }

    #[Transforms('array')]
    protected static function fromArray(array|object $value): array
    {
        return ((array) $value) + ['email' => 'davey@php.net'];
    }
}
