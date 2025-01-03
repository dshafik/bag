<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\Computed;
use Bag\Bag;
use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Support\Collection;
use ReflectionProperty;

readonly class ComputedValues
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        $computedProperties = Cache::remember(__METHOD__, $input->bagClassname, function () use ($input) {
            return collect(Reflection::getProperties($input->bag))->filter(function ($property) {
                /** @var ReflectionProperty $property */
                return Reflection::getAttribute($property, Computed::class) !== null;
            });
        });

        /** @var Collection<array-key,ReflectionProperty> $computedProperties */
        $computedProperties->each(function (ReflectionProperty $property) use ($input) {
            if ($property->isInitialized($input->bag)) {
                return;
            }

            throw new ComputedPropertyUninitializedException(sprintf('Property %s->%s must be computed', $input->bagClassname, $property->name));
        });

        return $input;
    }
}
