<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Bag;
use Bag\Pipelines\Pipes\CastInputValues;
use Bag\Pipelines\Pipes\ComputedValues;
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\FillBag;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingParameters;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Pipes\Validate;
use Bag\Pipelines\Values\BagInput;
use League\Pipeline\Pipeline;

class InputPipeline
{
    public static function process(BagInput $input): Bag
    {
        $pipeline = new Pipeline(
            null,
            new Transform(),
            new ProcessParameters(),
            new IsVariadic(),
            new MapInput(),
            new MissingParameters(),
            new ExtraParameters(),
            new Validate(),
            new CastInputValues(),
            new FillBag(),
            new ComputedValues(),
        );

        return $pipeline->process($input)->bag;
    }
}
