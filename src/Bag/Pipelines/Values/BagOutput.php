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
    public string $bagClassname;
    public ValueCollection $params;
    public ValueCollection $properties;
    public LaravelCollection $values;
    public LaravelCollection $output;
    public bool $variadic;

    public function __construct(
        public Bag $bag,
        public OutputType $outputType,
    ) {
        $this->bagClassname = $bag::class;
    }
}
