<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Bag;
use Bag\Pipelines\Pipes\CastInputValues;
use Bag\Pipelines\Pipes\ComputedValues;
use Bag\Pipelines\Pipes\DebugCollection;
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\FillBag;
use Bag\Pipelines\Pipes\FillNulls;
use Bag\Pipelines\Pipes\FillOptionals;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\LaravelRouteParameters;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingProperties;
use Bag\Pipelines\Pipes\ProcessArguments;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\StripExtraParameters;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Pipes\Validate;
use Bag\Pipelines\Values\BagInput;
use League\Pipeline\Pipeline;

class InputPipeline
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
            new MapInput(),
            new LaravelRouteParameters(),
            new FillOptionals(),
            new FillNulls(),
            new MissingProperties(),
            new ExtraParameters(),
            new StripExtraParameters(),
            new Validate(),
            new CastInputValues(),
            new FillBag(),
            new ComputedValues(),
            new DebugCollection(),
        );

        // @phpstan-ignore-next-line property.nonObject
        return $pipeline->process($input)->bag;
    }
}
