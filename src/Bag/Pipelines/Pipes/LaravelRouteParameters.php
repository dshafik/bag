<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Attributes\Laravel\FromRouteParameterProperty;
use Bag\Bag;
use Bag\Exceptions\InvalidRouteParameterException;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagInput;
use ReflectionParameter;

readonly class LaravelRouteParameters
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        if (!\function_exists('\request')) {
            return $input; // @codeCoverageIgnore
        }

        Reflection::getParameters(Reflection::getConstructor($input->bagClassname))->each(function ($parameter) use ($input) {
            /** @var ReflectionParameter $parameter */

            /** @var FromRouteParameter|FromRouteParameterProperty|null $attribute */
            $attribute = Reflection::getAttributeInstance($parameter, FromRouteParameter::class) ?? Reflection::getAttributeInstance($parameter, FromRouteParameterProperty::class);
            if ($attribute === null) {
                return;
            }

            $value = request()->route($attribute->parameterName ?? $parameter->getName());
            if ($value === null) {
                return;
            }

            if ($attribute instanceof FromRouteParameter) {
                $input->values[$parameter->getName()] = $value;

                return;
            }

            if (!\is_object($value)) {
                throw new InvalidRouteParameterException(sprintf('Route parameter "%s" must be an object.', $attribute->parameterName));
            }

            $value = $value->{$attribute->propertyName ?? $parameter->getName()};

            if ($value === null) {
                return;
            }

            $input->values[$parameter->getName()] = $value;
        });

        return $input;
    }
}
