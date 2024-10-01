<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Enums\OutputType;
use Bag\Pipelines\Values\BagOutput;

readonly class MapOutput
{
    public function __invoke(BagOutput $output): BagOutput
    {
        if ($output->outputType === OutputType::RAW) {
            $output->output = $output->values;

            return $output;
        }

        $aliases = $output->params->aliases();

        $output->output = $output->values->mapWithKeys(function (mixed $value, string $key) use ($aliases) {
            $key = $aliases['output'][$key] ?? $key;

            return [$key => $value];
        });

        return $output;
    }
}
