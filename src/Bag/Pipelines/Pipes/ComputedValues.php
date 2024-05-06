<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\Computed;
use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagInput;
use ReflectionProperty;

readonly class ComputedValues
{
    public function __invoke(BagInput $input, callable $next)
    {
        $computedProperties = Cache::remember(__METHOD__, $input->bagClassname, function () use ($input) {
            return collect(Reflection::getProperties($input->bag))->filter(function (ReflectionProperty $property) {
                return Reflection::getAttribute($property, Computed::class) !== null;
            });
        });

        $computedProperties->each(function (ReflectionProperty $property) use ($input) {
            if ($property->isInitialized($input->bag)) {
                return;
            }

            throw new ComputedPropertyUninitializedException(sprintf('Property %s->%s must be computed', $input->bagClassname, $property->name));
        });

        return $next($input);
    }
}
