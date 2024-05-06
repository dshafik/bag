<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Internal\Util;
use Bag\Pipelines\Pipes\CastOutputValues;
use Bag\Pipelines\Pipes\GetValues;
use Bag\Pipelines\Pipes\HideJsonValues;
use Bag\Pipelines\Pipes\HideValues;
use Bag\Pipelines\Pipes\MapOutput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Pipes\Wrap;
use Bag\Pipelines\Values\BagOutput;

class OutputPipeline
{
    public static function process(BagOutput $output): array
    {
        return Util::getPipeline()->send($output)
            ->through([
                ProcessParameters::class,
                ProcessProperties::class,
                GetValues::class,
                HideValues::class,
                HideJsonValues::class,
                CastOutputValues::class,
                MapOutput::class,
                Wrap::class,
            ])
            ->then(fn (BagOutput $data) => $output->output->toArray());
    }
}
