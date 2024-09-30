<?php

declare(strict_types=1);

namespace Bag\Pipelines\Values;

use Bag\Bag;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @internal
 * @template TBag of Bag
 */
class BagInput
{
    /**
     * @var TBag
     */
    public Bag $bag;

    /**
     * @var class-string<TBag>
     */
    public string $bagClassname;

    /**
     * @var ValueCollection<Value>
     */
    public ValueCollection $params;
    public bool $variadic;
    public LaravelCollection $values;

    /**
     * @param class-string<Bag> $bagClassname
     */
    public function __construct(
        string $bagClassname,
        public LaravelCollection $input,
    ) {
        /** @var class-string<TBag> $bagClassname */
        $this->bagClassname = $bagClassname;
    }
}
