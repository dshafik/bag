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
    public function __invoke(BagOutput $output, callable $next)
    {
        $output->properties = Cache::remember(__METHOD__, $output->bagClassname, function () use ($output) {
            $class = Reflection::getClass($output->bagClassname);

            $parameters = collect(Reflection::getParameters(Reflection::getConstructor($class)))->map(fn (ReflectionParameter $param) => $param->name);

            return ValueCollection::make(Reflection::getProperties($class))
                ->filter(fn (ReflectionProperty $property) => !$parameters->contains($property->name))
                ->mapWithKeys(function (ReflectionProperty $property) use ($class) {
                    return [$property->getName() => Value::create($class, $property)]; // @codeCoverageIgnore
                });
        });

        return $next($output);
    }
}
