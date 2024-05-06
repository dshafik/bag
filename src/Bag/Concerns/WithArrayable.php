<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Enums\OutputType;
use Bag\Pipelines\OutputPipeline;
use Bag\Pipelines\Values\BagOutput;

trait WithArrayable
{
    public function toArray(): array
    {
        $output = new BagOutput(bag: $this, outputType: OutputType::ARRAY);

        return OutputPipeline::process($output);
    }
}
