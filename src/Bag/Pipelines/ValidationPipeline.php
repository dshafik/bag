<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingParameters;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Pipes\Validate;
use Bag\Pipelines\Values\BagInput;
use League\Pipeline\Pipeline;

class ValidationPipeline
{
    public static function process(BagInput $input): bool
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
        );

        $pipeline->process($input);

        return true;
    }
}
