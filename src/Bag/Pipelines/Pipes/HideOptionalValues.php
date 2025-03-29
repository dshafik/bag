<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagOutput;
use Bag\Values\Optional;

readonly class HideOptionalValues
{
    public function __invoke(BagOutput $output): BagOutput
    {
        $output->values = $output->values->filter(function (mixed $value) {
            return !($value instanceof Optional);
        });

        return $output;
    }
}
