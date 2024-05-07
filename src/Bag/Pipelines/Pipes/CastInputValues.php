<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;

readonly class CastInputValues
{
    public function __invoke(BagInput $input)
    {
        $properties = $input->params;
        $values = $input->values;

        $input->values = $input->values->map(function ($value, $key) use ($properties, $values) {
            if (!isset($properties[$key])) {
                return $this->castVariadic($properties, $values, $value);
            }

            /** @var Value $property */
            $property = $properties[$key];

            return ($property->inputCast)($values);
        });

        return $input;
    }

    protected function castVariadic(ValueCollection $properties, $values, $value): mixed
    {
        /** @var Value $last */
        $last = $properties->last();

        return ($last->inputCast)($values->put($last->name, $value));
    }
}
