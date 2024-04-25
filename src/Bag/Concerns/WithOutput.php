<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Collection;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionClass;

trait WithOutput
{
    protected static function prepareOutputValues(Collection $values): Collection
    {
        $properties = self::getProperties(new ReflectionClass(static::class));

        $aliases = $properties->aliases();

        return self::getOutputValues($values, $properties, $aliases);
    }

    protected static function getOutputValues(Collection $values, ValueCollection $properties, LaravelCollection $aliases): Collection
    {
        return $values->mapWithKeys(function (mixed $_, string $key) use ($properties, $aliases, $values) {
            /** @var Value $property */
            $property = $properties[$key];

            if (isset($aliases['output'][$key])) {
                $key = $aliases['output'][$key];
            }

            $value = ($property->outputCast)($values);

            return [$key => $value];
        });
    }

    protected function getBag(): Collection
    {
        $value = $this;

        return Collection::make((fn (): array => \get_object_vars($value))->bindTo(null)());
    }

    abstract protected static function getProperties(ReflectionClass $class): ValueCollection;
}
