<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Internal\Util;
use Bag\Pipelines\Pipes\WrapCollection;
use Bag\Pipelines\Values\CollectionOutput;
use Illuminate\Support\Collection as LaravelCollection;

class OutputCollectionPipeline
{
    public static function process(CollectionOutput $output): LaravelCollection
    {
        return Util::getPipeline()->send($output)
            ->through([
                WrapCollection::class
            ])
            ->then(fn (CollectionOutput $output) => $output->collection);
    }
}
