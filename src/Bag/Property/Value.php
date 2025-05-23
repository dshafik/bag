<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Bag;
use Bag\Collection;
use Bag\Internal\Util;
use Bag\Values\Optional;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionClass;
use ReflectionParameter;
use ReflectionProperty;

class Value
{
    /**
     * @param ReflectionClass<Bag|LaravelCollection<array-key, mixed>> $bag
     * @param Collection<string> $type
     */
    public function __construct(
        public ReflectionClass $bag,
        public ReflectionProperty|ReflectionParameter $property,
        public Collection $type,
        public string $name,
        public bool $optional,
        public bool $required,
        public bool $allowsNull,
        public MapCollection $maps,
        public CastInput $inputCast,
        public CastOutput $outputCast,
        public ValidatorCollection $validators,
        public bool $variadic,
    ) {
    }

    /**
     * @param ReflectionClass<Bag|LaravelCollection<array-key, mixed>> $bag
     */
    public static function create(
        ReflectionClass $bag,
        ReflectionProperty|ReflectionParameter $property,
    ): self {
        $name = $property->getName();

        $type = Util::getPropertyTypes($property);

        $isOptional = Util::getPropertyTypes($property)->contains(Optional::class);

        return new self(
            bag: $bag,
            property: $property,
            type: $type,
            name: $name,
            optional: $isOptional,
            required: self::isRequired($property, $isOptional),
            allowsNull: self::allowsNull($property),
            maps: MapCollection::create(bagClass: $bag, property: $property),
            inputCast: CastInput::create(property: $property),
            outputCast: CastOutput::create(property: $property),
            validators: ValidatorCollection::create(property: $property),
            variadic: self::isVariadic($property),
        );
    }

    protected static function isRequired(ReflectionProperty|ReflectionParameter $property, bool $isOptional = false): bool
    {
        if ($isOptional) {
            return false;
        }

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

    protected static function allowsNull(ReflectionParameter|ReflectionProperty $property): bool
    {
        if ($property instanceof ReflectionParameter) {
            return $property->allowsNull();
        }

        return $property->getType()?->allowsNull() ?? true;
    }
}
