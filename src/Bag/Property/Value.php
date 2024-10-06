<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Bag;
use Bag\Internal\Util;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;

readonly class Value
{
    /**
     * @param ReflectionClass<Bag|Collection<array-key, mixed>> $bag
     */
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

    /**
     * @param ReflectionClass<Bag|Collection<array-key, mixed>> $bag
     */
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

    protected static function isRequired(ReflectionProperty|ReflectionParameter $property): bool
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

    protected static function isVariadic(ReflectionProperty|ReflectionParameter $property): bool
    {
        if ($property instanceof ReflectionParameter) {
            return $property->isVariadic();
        }

        return false;
    }
}
