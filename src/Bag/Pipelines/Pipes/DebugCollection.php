<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\DebugBar\Collectors\BagCollector;
use Bag\Pipelines\Values\BagInput;

readonly class DebugCollection
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        BagCollector::add($input->bag);

        return $input;
    }

}
