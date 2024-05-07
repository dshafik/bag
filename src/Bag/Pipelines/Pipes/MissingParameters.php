<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Exceptions\MissingPropertiesException;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Support\Collection;

readonly class MissingParameters
{
    public function __invoke(BagInput $input)
    {
        /** @var Collection $required */
        $required = $input->params->required();
        $input->values->each(fn ($_, $key) => $required->forget($key));

        $required->whenNotEmpty(fn () => throw new MissingPropertiesException($required));

        return $input;
    }

}
