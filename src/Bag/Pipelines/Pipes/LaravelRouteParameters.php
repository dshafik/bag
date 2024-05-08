<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Attributes\Laravel\FromRouteParameterProperty;
use Bag\Exceptions\InvalidRouteParameterException;
use Bag\Internal\Reflection;
use Bag\Pipelines\Values\BagInput;
use ReflectionParameter;

readonly class LaravelRouteParameters
{
    public function __invoke(BagInput $input)
    {
        if (!\function_exists('\request')) {
            return $input; // @codeCoverageIgnore
        }

        collect(Reflection::getParameters(Reflection::getConstructor($input->bagClassname)))->each(function (ReflectionParameter $parameter) use ($input) {
            if (($attribute = Reflection::getAttributeInstance($parameter, FromRouteParameter::class)) === null) {
                if (($attribute = Reflection::getAttributeInstance($parameter, FromRouteParameterProperty::class)) === null) {
                    return;
                }
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
