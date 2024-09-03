<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Exceptions\MissingPropertiesException;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Support\Collection;

readonly class MissingProperties
{
    public function __invoke(BagInput $input)
    {
        /** @var Collection $required */
        $required = $input->params->required();
        $input->values->each(fn (mixed $_, string $key) => $required->forget($key));

        $required->whenNotEmpty(fn () => throw new MissingPropertiesException($input->bagClassname, $required));

        return $input;
    }

}
