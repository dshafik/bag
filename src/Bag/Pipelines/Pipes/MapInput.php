<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagInput;

readonly class MapInput
{
    public function __invoke(BagInput $input)
    {
        $aliases = $input->params->aliases();

        $input->values = $input->input->mapWithKeys(function (mixed $value, string $key) use ($aliases) {
            $key = $aliases['input'][$key] ?? $key;

            return [$key => $value];
        });

        return $input;
    }
}
