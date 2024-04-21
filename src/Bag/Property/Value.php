<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\MapName;
use Bag\Util;
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
        public Map $maps,
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

        $maps = $bag->getAttributes(name: MapName::class);
        $map = null;
        if (count($maps) > 0) {
            $map = $maps[0]->newInstance();
        }

        return new self(
            bag: $bag,
            property: $property,
            type: $type,
            name: $name,
            required: ! $property->isOptional(),
            maps: Map::create(classMap: $map, property: $property),
            inputCast: CastInput::create(property: $property),
            outputCast: CastOutput::create(property: $property),
            validators: ValidatorCollection::create(property: $property),
            variadic: $property->isVariadic(),
        );
    }
}
