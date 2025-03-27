<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Bag\Values\Optional;
use ReflectionParameter;
use ReflectionProperty;

readonly class FillOptionals
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        $input->params->optional()->each(function (Value $param) use ($input) {
            /** @var Value $param */
            $hasValue = match(true) {
                $input->values->has($param->name)  => true,
                $param->property instanceof ReflectionParameter && $param->property->isDefaultValueAvailable() => true,
                $param->property instanceof ReflectionProperty && $param->property->hasDefaultValue() => true,
                default => false
            };

            if ($hasValue && !(!$param->allowsNull && $input->values->get($param->name) === null)) {
                return;
            }

            $input->values->put($param->name, new Optional());
        });

        return $input;
    }
}
