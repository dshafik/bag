<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Transforms;
use Bag\Bag;
use Bag\Enums\OutputType;
use Bag\Pipelines\OutputPipeline;
use Bag\Pipelines\Values\BagOutput;

trait WithJson
{
    public function toJson($options = 0): string|false
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @return array<array-key, mixed>
     */
    public function jsonSerialize(): array
    {
        $output = new BagOutput(bag: $this, outputType: OutputType::JSON);

        return OutputPipeline::process($output);
    }

    #[Transforms(Bag::FROM_JSON)]
    protected static function fromJsonString(string $json): mixed
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
