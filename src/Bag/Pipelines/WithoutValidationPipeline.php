<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Bag;
use Bag\Pipelines\Pipes\CastInputValues;
use Bag\Pipelines\Pipes\ComputedValues;
use Bag\Pipelines\Pipes\FillBag;
use Bag\Pipelines\Pipes\FillNulls;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\LaravelRouteParameters;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingProperties;
use Bag\Pipelines\Pipes\ProcessArguments;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\StripExtraParameters;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Values\BagInput;
use League\Pipeline\Pipeline;

class WithoutValidationPipeline
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
            new Transform(),
            new ProcessParameters(),
            new ProcessArguments(),
            new IsVariadic(),
            new FillNulls(),
            new MapInput(),
            new LaravelRouteParameters(),
            new MissingProperties(),
            new StripExtraParameters(),
            new CastInputValues(),
            new FillBag(),
            new ComputedValues(),
        );

        return $pipeline->process($input)->bag;
    }
}
