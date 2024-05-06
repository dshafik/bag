<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagInput;

readonly class IsVariadic
{
    public function __invoke(BagInput $input, callable $next)
    {
        $input->variadic = $input->params->last()->variadic;

        return $next($input);
    }
}
