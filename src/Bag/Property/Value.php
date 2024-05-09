<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Internal\Util;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;

class Value
{
    public function __construct(
        public ReflectionClass $bag,
        public ReflectionProperty|ReflectionParameter $property,
        public ReflectionNamedType $type,
        public string $name,
        public bool $required,
        public MapCollection $maps,
        public CastInput $inputCast,
        public CastOutput $outputCast,
        public ValidatorCollection $validators,
        public bool $variadic,
    ) {
    }

    public static function create(
        ReflectionClass $bag,
        ReflectionProperty|ReflectionParameter $property,
    ): self {
        $name = $property->getName();

        $type = Util::getPropertyType($property);

        return new self(
            bag: $bag,
            property: $property,
            type: $type,
            name: $name,
            required: self::isRequired($property),
            maps: MapCollection::create(bagClass: $bag, property: $property),
            inputCast: CastInput::create(property: $property),
            outputCast: CastOutput::create(property: $property),
            validators: ValidatorCollection::create(property: $property),
            variadic: self::isVariadic($property),
        );
    }

    protected static function isRequired(ReflectionProperty|ReflectionParameter $property)
    {
        if ($property instanceof ReflectionParameter) {
            return !$property->isOptional();
        }

        /** @var ReflectionProperty $property */
        if ($property->hasDefaultValue()) {
            return false;
        }

        return true;
    }

    protected static function isVariadic(ReflectionProperty|ReflectionParameter $property)
    {
        if ($property instanceof ReflectionParameter) {
            return $property->isVariadic();
        }

        return false;
    }
}
