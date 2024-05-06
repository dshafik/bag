<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagInput;

readonly class FillBag
{
    public function __invoke(BagInput $input, callable $next)
    {
        $input->bag = new ($input->bagClassname)(... $input->values);

        return $next($input);
    }
}
