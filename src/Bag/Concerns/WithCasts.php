<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Collection;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;

trait WithCasts
{
    protected static function castValues(Collection $values, LaravelCollection $aliases, ValueCollection $properties, LaravelCollection $extraProperties): mixed
    {
        $values = $values->map(function ($value, $inputKey) use ($values, $aliases, $properties, $extraProperties) {
            $key = $aliases['input'][$inputKey] ?? $inputKey;

            if (isset($extraProperties[$key])) {
                /** @var Value $last */
                $last = $properties->last();

                return ($last->inputCast)('extra', collect(['extra' => $value]));
            }

            /** @var Value $property */
            $property = $properties[$key];

            return ($property->inputCast)($key, $values);
        });

        return $values;
    }
}
