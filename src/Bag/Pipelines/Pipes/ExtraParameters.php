<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Pipelines\Values\BagInput;

readonly class ExtraParameters
{
    public function __invoke(BagInput $input, callable $next)
    {
        if ($input->variadic) {
            return $next($input);
        }

        $extra = collect();
        $input->values->each(function ($_, $key) use ($input, $extra) {
            if ($input->params->has($key)) {
                return;
            }

            $extra->add($key);
        });

        $extra->whenNotEmpty(fn () => throw new AdditionalPropertiesException($extra));

        return $next($input);
    }
}
