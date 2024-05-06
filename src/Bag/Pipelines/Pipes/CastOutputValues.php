<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection;

readonly class CastOutputValues
{
    public function __invoke(BagOutput $output, callable $next)
    {
        $properties = $output->properties;
        $params = $output->params;
        $values = $output->values;

        $output->values = $output->values->map(function ($value, $key) use ($properties, $params, $values) {
            if ($params[$key]->variadic) {
                return $this->castVariadic($params, $values, $value);
            }

            /** @var Value $value */
            $value = $properties[$key] ?? $params[$key];

            return ($value->outputCast)($values);
        });

        return $next($output);
    }

    protected function castVariadic(ValueCollection $params, $values, $value): mixed
    {
        /** @var Value $last */
        $last = $params->last();

        return Collection::wrap($value)->map(function ($value) use ($last, $values) {
            return ($last->outputCast)($values->put($last->name, $value));
        })->toArray();
    }
}
