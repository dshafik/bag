<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection;

readonly class CastInputValues
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        $properties = $input->params;
        $values = $input->values;

        $input->values = $input->values->map(function (mixed $value, string $key) use ($properties, $values) {
            if (!isset($properties[$key])) {
                return $this->castVariadic($properties, $values, $value);
            }

            /** @var Value $property */
            $property = $properties[$key];

            return ($property->inputCast)($values);
        });

        return $input;
    }

    /**
     * @param Collection<array-key, mixed> $values
     */
    protected function castVariadic(ValueCollection $properties, Collection $values, mixed $value): mixed
    {
        /** @var Value $last */
        $last = $properties->last();

        return ($last->inputCast)($values->put($last->name, $value));
    }
}
