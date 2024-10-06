<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\Cast;
use Bag\Attributes\CastInput as CastInputAttribute;
use Bag\Casts\CastsPropertySet;
use Bag\Casts\MagicCast;
use Bag\Internal\Reflection;
use Bag\Internal\Util;
use Illuminate\Support\Collection;

readonly class CastInput
{
    public function __construct(
        protected string $propertyType,
        protected string $name,
        protected Cast|CastInputAttribute $caster
    ) {
    }

    public static function create(\ReflectionParameter|\ReflectionProperty $property): self
    {
        $cast = null;

        $castAttribute = Reflection::getAttribute($property, Cast::class);
        if ($castAttribute !== null) {
            $cast = Reflection::getAttributeInstance($castAttribute);
            $args = Reflection::getAttributeArguments($castAttribute);
            $casterClass = $args[\array_key_first($args)];
            if ((is_string($casterClass) || is_object($casterClass)) && !\is_a($casterClass, CastsPropertySet::class, true)) {
                $cast = null;
            }
        }

        $castAttribute = Reflection::getAttribute($property, CastInputAttribute::class);
        if ($castAttribute !== null) {
            $cast = Reflection::getAttributeInstance($castAttribute);
        }

        $type = Util::getPropertyType($property);

        return new self(propertyType: $type->getName(), name: $property->name, caster: $cast ?? new CastInputAttribute(MagicCast::class));
    }

    /**
     * @param Collection<array-key, mixed> $properties
     */
    public function __invoke(Collection $properties): mixed
    {
        return $this->caster->cast(propertyType: $this->propertyType, propertyName: $this->name, properties: $properties);
    }
}
