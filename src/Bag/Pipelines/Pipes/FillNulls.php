<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use ReflectionParameter;
use ReflectionProperty;

readonly class FillNulls
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        // Get a list of missing nullable values
        $input->params->nullable()->each(function ($param) use ($input) {
            /** @var Value $param */
            $hasValue = match(true) {
                $input->input->has($param->name) => true,
                $param->property instanceof ReflectionParameter && $param->property->isDefaultValueAvailable() => true,
                $param->property instanceof ReflectionProperty && $param->property->hasDefaultValue() => true,
                default => false
            };

            if ($hasValue) {
                return;
            }

            $input->input->put($param->name, null);
        });

        return $input;
    }
}
