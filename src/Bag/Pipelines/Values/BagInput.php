<?php

declare(strict_types=1);

namespace Bag\Pipelines\Values;

use Bag\Bag;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @internal
 * @template T of Bag
 */
class BagInput
{
    /**
     * @var T
     */
    public Bag $bag;

    /**
     * @var ValueCollection<Value>
     */
    public ValueCollection $params;

    /**
     * @var LaravelCollection<array-key, mixed>
     */
    public LaravelCollection $values;

    public bool $variadic;

    /**
     * @param class-string<T> $bagClassname
     * @param LaravelCollection<array-key, mixed> $input
     */
    public function __construct(
        public string $bagClassname,
        public LaravelCollection $input,
    ) {
    }
}
