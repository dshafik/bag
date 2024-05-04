<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Collection;
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Exceptions\MissingPropertiesException;
use Bag\Property\ValueCollection;
use Bag\Reflection;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionClass;

trait WithInput
{
    protected static function prepareInputValues(Collection $values): Collection
    {
        $properties = self::getProperties(Reflection::getClass(static::class));

        $requiredProperties = $properties->required();
        $aliases = $properties->aliases();
        $extraProperties = collect();

        $isVariadic = self::isVariadic($properties);

        [$properties, $requiredProperties, $extraProperties, $aliases, $values] = self::getInputValues($values, $properties, $requiredProperties, $extraProperties, $aliases);

        static::validate($values);

        $requiredProperties->whenNotEmpty(fn () => throw new MissingPropertiesException($requiredProperties));
        if (! $isVariadic) {
            $extraProperties->whenNotEmpty(fn () => throw new AdditionalPropertiesException($extraProperties));
        }

        $values = self::castValues($values, $aliases, $properties, $extraProperties);

        return $values;
    }

    protected static function getInputValues(Collection $values, ValueCollection $properties, ValueCollection $requiredProperties, LaravelCollection $extraProperties, LaravelCollection $aliases): array
    {
        $values = $values->mapWithKeys(function (mixed $value, string $inputKey) use (&$properties, &$requiredProperties, &$extraProperties, &$aliases) {
            $key = $aliases['input'][$inputKey] ?? $inputKey;

            if (! isset($properties[$key])) {
                $extraProperties[$key] = true;

                return [$key => $value];
            }

            if (isset($requiredProperties[$key])) {
                unset($requiredProperties[$key]);
            }

            return [$key => $value];
        });

        return [$properties, $requiredProperties, $extraProperties, $aliases, $values];
    }

    abstract protected static function getProperties(ReflectionClass $class): ValueCollection;

    abstract protected static function isVariadic(ValueCollection $properties): bool;

    abstract protected static function castValues(Collection $values, LaravelCollection $aliases, ValueCollection $properties, LaravelCollection $extraProperties): mixed;

    abstract protected static function validate(LaravelCollection|array $values);
}
