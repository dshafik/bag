<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Pipelines\Pipes\WrapCollection;
use Bag\Pipelines\Values\CollectionOutput;
use Illuminate\Support\Collection as LaravelCollection;
use League\Pipeline\Pipeline;

readonly class OutputCollectionPipeline
{
    /**
     * @return LaravelCollection<array-key,mixed>
     */
    public static function process(CollectionOutput $output): LaravelCollection
    {
        $pipeline = new Pipeline(null, new WrapCollection());

        return $pipeline->process($output)->collection;
    }
}
