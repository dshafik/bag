<?php

declare(strict_types=1);

namespace Bag\Pipelines\Values;

use Bag\Enums\OutputType;
use Illuminate\Support\Collection as LaravelCollection;

class CollectionOutput
{
    public function __construct(
        public LaravelCollection $collection,
        public OutputType $outputType,
    ) {
    }
}
