<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Bag;
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\FillNulls;
use Bag\Pipelines\Pipes\FillOptionals;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingProperties;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Pipes\Validate;
use Bag\Pipelines\Values\BagInput;
use League\Pipeline\Pipeline;

class ValidationPipeline
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     */
    public static function process(BagInput $input): bool
    {
        $pipeline = new Pipeline(
            null,
            new Transform(),
            new ProcessParameters(),
            new IsVariadic(),
            new MapInput(),
            new FillOptionals(),
            new FillNulls(),
            new MissingProperties(),
            new ExtraParameters(),
            new Validate(),
        );

        $pipeline->process($input);

        return true;
    }
}
