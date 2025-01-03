<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use ReflectionParameter;
use ReflectionProperty;

readonly class ProcessProperties
{
    public function __invoke(BagOutput $output): BagOutput
    {
        $output->properties = Cache::remember(__METHOD__, $output->bagClassname, function () use ($output) {
            $class = Reflection::getClass($output->bagClassname);

            $parameters = Reflection::getParameters(Reflection::getConstructor($class))->map(function ($param) {
                /** @var ReflectionParameter $param */
                return $param->name;
            });

            return ValueCollection::wrap(collect(Reflection::getProperties($class))
                ->filter(function ($property) use ($parameters) {
                    /** @var ReflectionProperty $property */
                    return !$parameters->contains($property->name);
                })
                ->mapWithKeys(function ($property) use ($class) {
                    /** @var ReflectionProperty $property */
                    return [$property->getName() => Value::create($class, $property)]; // @codeCoverageIgnore
                }));
        });

        return $output;
    }
}
