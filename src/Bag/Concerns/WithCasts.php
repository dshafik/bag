<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Property\Value;

trait WithCasts
{
    protected static function castValues(mixed $values, mixed $aliases, mixed $properties, mixed $extraProperties): mixed
    {
        $values = $values->map(function ($value, $inputKey) use ($values, $aliases, $properties, $extraProperties) {
            $key = $aliases['input'][$inputKey] ?? $inputKey;

            if (isset($extraProperties[$key])) {
                return $value;
            }

            /** @var Value $property */
            $property = $properties[$key];

            return ($property->inputCast)($key, $values);
        });
        return $values;
    }
}
