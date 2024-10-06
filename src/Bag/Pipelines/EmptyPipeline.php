<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Bag;
use Bag\Pipelines\Pipes\FillBag;
use Bag\Pipelines\Pipes\FillDefaultValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use League\Pipeline\Pipeline;

readonly class EmptyPipeline
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return T
     */
    public static function process(BagInput $input): Bag
    {
        $pipeline = new Pipeline(
            null,
            new ProcessParameters(),
            new FillDefaultValues(),
            new FillBag(),
        );

        return $pipeline->process($input)->bag;
    }
}
