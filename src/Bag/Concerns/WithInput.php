<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Collection;
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Exceptions\MissingPropertiesException;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionClass;

trait WithInput
{
    protected static function prepareInputValues(Collection $values): Collection
    {
        $class = new ReflectionClass(static::class);
        $properties = self::getProperties($class);

        $requiredProperties = $properties->required();
        $aliases = $properties->aliases();
        $extraProperties = collect();

        [$variadic, $isVariadic] = self::variadic($class, $properties);

        [$properties, $requiredProperties, $extraProperties, $aliases, $values] = self::getInputValues($values, $variadic, $properties, $requiredProperties, $extraProperties, $aliases);

        static::validate($values);

        $values = self::castValues($values, $aliases, $properties, $extraProperties);

        $requiredProperties->whenNotEmpty(fn () => throw new MissingPropertiesException($requiredProperties));
        if (!$isVariadic) {
            $extraProperties->whenNotEmpty(fn () => throw new AdditionalPropertiesException($extraProperties));
        }

        return $values;
    }

    protected static function getInputValues(Collection $values, mixed $variadic, ValueCollection $properties, ValueCollection $requiredProperties, LaravelCollection $extraProperties, LaravelCollection $aliases): array
    {
        $values = $values->mapWithKeys(function ($value, $inputKey) use ($variadic, &$properties, &$requiredProperties, &$extraProperties, &$aliases) {
            $key = $aliases['input'][$inputKey] ?? $inputKey;

            if (!isset($properties[$key])) {
                $extraProperties[$key] = true;

                if ($variadic !== null) {
                    /** @var Value $last */
                    $last = $properties->last();
                    if ($last->variadic) {
                        $propertyType = $last->type;
                    } else {
                        $propertyType = \gettype($value);
                    }

                    $variadic->cast($propertyType, $key, $properties);
                }

                return [$key => $value];
            }

            if (isset($requiredProperties[$key])) {
                unset($requiredProperties[$key]);
            }

            return [$key => $value];
        });
        return array($properties, $requiredProperties, $extraProperties, $aliases, $values);
    }

    abstract protected static function getProperties(ReflectionClass $class): ValueCollection;
    abstract protected static function variadic(ReflectionClass $class, ValueCollection $properties): array;
    abstract protected static function castValues(mixed $values, mixed $aliases, mixed $properties, mixed $extraProperties): mixed;
    abstract protected static function validate(LaravelCollection|array $values);
}
