<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Collection;
use Bag\Enums\OutputType;
use Bag\Pipelines\OutputPipeline;
use Bag\Pipelines\Values\BagOutput;

trait WithOutput
{
    public function get(?string $key = null): mixed
    {
        $output = new BagOutput(bag: $this, outputType: OutputType::RAW);

        $values = OutputPipeline::process($output);

        if ($key !== null) {
            return $values[$key] ?? null;
        }

        return $values;
    }

    public function getRaw(?string $key = null): mixed
    {
        $value = $this;

        $values = Collection::make(get_object_vars($value));
        if ($key !== null) {
            return $values[$key];
        }

        return $values;
    }

    public function unwrapped()
    {
        $output = new BagOutput(bag: $this, outputType: OutputType::UNWRAPPED);

        return OutputPipeline::process($output);
    }
}
