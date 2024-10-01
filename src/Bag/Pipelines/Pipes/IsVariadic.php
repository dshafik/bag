<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;

readonly class IsVariadic
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        /** @var Value $lastParam */
        $lastParam = $input->params->last();
        $input->variadic = $lastParam->variadic;

        return $input;
    }
}
