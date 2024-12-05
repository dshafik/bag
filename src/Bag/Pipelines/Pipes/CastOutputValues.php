<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection;

readonly class CastOutputValues
{
    public function __invoke(BagOutput $output): BagOutput
    {
        $properties = $output->properties;
        $params = $output->params;
        $values = $output->values;

        $output->values = $output->values->map(function (mixed $value, string $key) use ($properties, $params, $values) {
            if (isset($params[$key]) && $params[$key]->variadic) {
                return $this->castVariadic($params, $values, $value);
            }

            /** @var Value $value */
            $value = $properties[$key] ?? $params[$key];

            return ($value->outputCast)($values);
        });

        return $output;
    }

    /**
     * @param Collection<array-key, mixed> $values
     */
    protected function castVariadic(ValueCollection $params, Collection $values, mixed $value): mixed
    {
        /** @var Value $last */
        $last = $params->last();

        return Collection::wrap($value)->map(function (mixed $value) use ($last, $values) {
            return ($last->outputCast)($values->put($last->name, $value));
        })->toArray();
    }
}
