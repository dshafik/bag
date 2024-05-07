<?php

declare(strict_types=1);

namespace Bag\Pipelines;

use Bag\Pipelines\Pipes\CastOutputValues;
use Bag\Pipelines\Pipes\GetValues;
use Bag\Pipelines\Pipes\HideJsonValues;
use Bag\Pipelines\Pipes\HideValues;
use Bag\Pipelines\Pipes\MapOutput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Pipes\Wrap;
use Bag\Pipelines\Values\BagOutput;
use League\Pipeline\Pipeline;

class OutputPipeline
{
    public static function process(BagOutput $output): array
    {
        $pipeline = new Pipeline(
            null,
            new ProcessParameters(),
            new ProcessProperties(),
            new GetValues(),
            new HideValues(),
            new HideJsonValues(),
            new CastOutputValues(),
            new MapOutput(),
            new Wrap(),
        );

        return $pipeline->process($output)->output->toArray();
    }
}
