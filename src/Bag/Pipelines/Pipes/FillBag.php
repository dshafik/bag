<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagInput;

readonly class FillBag
{
    public function __invoke(BagInput $input)
    {
        $input->bag = new ($input->bagClassname)(... $input->values);

        return $input;
    }
}
