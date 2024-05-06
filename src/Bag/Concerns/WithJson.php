<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Enums\OutputType;
use Bag\Pipelines\OutputPipeline;
use Bag\Pipelines\Values\BagOutput;

trait WithJson
{
    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize(): array
    {
        $output = new BagOutput(bag: $this, outputType: OutputType::JSON);

        return OutputPipeline::process($output);
    }
}
