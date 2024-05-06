<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagOutput;

final class GetValues
{
    public function __invoke(BagOutput $output, callable $next)
    {
        $output->values = $output->bag->getRaw();

        return $next($output);
    }
}
