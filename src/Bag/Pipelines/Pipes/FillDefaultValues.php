<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Brick\Money\Money;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Locale;
use NumberFormatter;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use stdClass;
use TypeError;
use UnitEnum;

readonly class FillDefaultValues
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        /** @var Collection<array-key, mixed> $defaults */
        $defaults = $input->input;
        /** @var Value $param */
        foreach ($input->params as $param) {
            if ($defaults->has($param->name)) {
                continue;
            }

            /** @var ReflectionNamedType $type */
            $type = $param->property->getType();
            $parameterType = $type->getName();

            $defaults->put($param->name, match (true) {
                ($param->property instanceof ReflectionProperty) && $param->property->hasDefaultValue() => $param->property->getDefaultValue(),
                ($param->property instanceof ReflectionParameter) && $param->property->isDefaultValueAvailable() => $param->property->getDefaultValue(),
                $type->allowsNull() => null,
                default => $this->getEmptyValue($type, $parameterType)
            });
        }

        $input->values = $defaults;

        return $input;
    }

    protected function getEmptyValue(ReflectionNamedType $type, string $parameterType): mixed
    {
        return match (true) {
            $type->isBuiltin() => match ($parameterType) {
                'int' => 0,
                'float' => 0.0,
                'bool' => false,
                'string' => '',
                'array' => [],
                'object' => new stdClass(),
                default => throw new TypeError('Unsupported type ' . $parameterType),
            },
            \class_exists($parameterType) && \method_exists($parameterType, 'empty') => $parameterType::empty(),
            is_subclass_of($parameterType, DateTimeInterface::class) => new $parameterType('1970-01-01 00:00:00'),
            $parameterType === Money::class => Money::zero(NumberFormatter::create(Locale::getDefault(), NumberFormatter::CURRENCY)->getTextAttribute(NumberFormatter::CURRENCY_CODE)),
            \is_subclass_of($parameterType, UnitEnum::class) => collect($parameterType::cases())->first(),
            \is_subclass_of($parameterType, Model::class) => $parameterType::make(),
            default => new TypeError('Unsupported type ' . $parameterType),
        };
    }
}
