<?php

declare(strict_types=1);

namespace Bag\Pipelines\Values;

use Bag\Bag;
use Bag\Enums\OutputType;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @internal
 */
class BagOutput
{
    /**
     * @var class-string<Bag>
     */
    public string $bagClassname;
    public ValueCollection $params;
    public ValueCollection $properties;
    /**
     * @var LaravelCollection<array-key, mixed>
     */
    public LaravelCollection $values;
    /**
     * @var LaravelCollection<array-key, mixed>
     */
    public LaravelCollection $output;
    public bool $variadic;

    public function __construct(
        public Bag $bag,
        public OutputType $outputType,
    ) {
        $this->bagClassname = $bag::class;
    }
}
