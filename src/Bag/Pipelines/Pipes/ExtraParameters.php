<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Pipelines\Values\BagInput;

readonly class ExtraParameters
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        if ($input->variadic) {
            return $input;
        }

        $extra = collect();
        $input->values->each(function (mixed $_, string $key) use ($input, $extra) {
            if ($input->params->has($key)) {
                return;
            }

            $extra->add($key);
        });

        $extra->whenNotEmpty(fn () => throw new AdditionalPropertiesException($input->bagClassname, $extra));

        return $input;
    }
}
