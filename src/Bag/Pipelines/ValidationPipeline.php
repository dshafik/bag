<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Internal\Util;
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingParameters;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Pipes\Validate;
use Bag\Pipelines\Values\BagInput;

class ValidationPipeline
{
    public static function process(BagInput $data): bool
    {
        return Util::getPipeline()->send($data)
            ->through([
                Transform::class,
                ProcessParameters::class,
                IsVariadic::class,
                MapInput::class,
                Validate::class,
                MissingParameters::class,
                ExtraParameters::class,
            ])
            ->then(fn () => true);
    }
}
