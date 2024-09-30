<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;

readonly class ProcessArguments
{
    public function __invoke(BagInput $input)
    {
        $firstInput = $input->input->first();
        if (is_array($firstInput) && $input->input->count() === 1 && !\ctype_digit((string) key($firstInput))) {
            $input->input = collect($firstInput);

            return $input;
        }

        if (\ctype_digit((string) $input->input->keys()->first())) {
            $input->params->each(function (Value $param, string $key) use ($input) {
                static $i = 0;
                $input->input->put($key, $input->input->pull($i));
                $i++;
            });
        }

        return $input;
    }
}
