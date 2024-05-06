<?php

declare(strict_types=1);

namespace Bag\Pipelines\Values;

use Bag\Bag;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @internal
 */
class BagInput
{
    public Bag $bag;

    public ValueCollection $params;
    public bool $variadic;
    public LaravelCollection $values;

    public function __construct(
        public string $bagClassname,
        public mixed $input,
    ) {
    }
}
