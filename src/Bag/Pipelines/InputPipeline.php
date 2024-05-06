<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Bag;
use Bag\Internal\Util;
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

class InputPipeline
{
    public static function process(BagInput $input): Bag
    {
        return Util::getPipeline()->send($input)
            ->through([
                Transform::class,
                ProcessParameters::class,
                IsVariadic::class,
                MapInput::class,
                Validate::class,
                MissingParameters::class,
                ExtraParameters::class,
                CastInputValues::class,
                FillBag::class,
                ComputedValues::class,
            ])
            ->then(fn (BagInput $data) => $input->bag);
    }
}
