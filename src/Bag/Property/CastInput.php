<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\Cast;
use Bag\Attributes\CastInput as CastInputAttribute;
use Bag\Casts\CastsPropertySet;
use Bag\Casts\MagicCast;
use Bag\Collection;
use Bag\Internal\Reflection;
use Bag\Internal\Util;
use Illuminate\Support\Collection as LaravelCollection;

class CastInput
{
    public function __construct(
        protected Collection $propertyTypes,
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
            $casterClass = $args->first();
            if ((is_string($casterClass) || is_object($casterClass)) && !\is_a($casterClass, CastsPropertySet::class, true)) {
                $cast = null;
            }
        }

        $castAttribute = Reflection::getAttribute($property, CastInputAttribute::class);
        if ($castAttribute !== null) {
            $cast = Reflection::getAttributeInstance($castAttribute);
        }

        $types = Collection::wrap(Util::getPropertyTypes($property));

        return new self(propertyTypes: $types, name: $property->name, caster: $cast ?? new CastInputAttribute(MagicCast::class));
    }

    /**
     * @param LaravelCollection<array-key, mixed> $properties
     */
    public function __invoke(LaravelCollection $properties): mixed
    {
        return $this->caster->cast(propertyType: $this->propertyTypes, propertyName: $this->name, properties: $properties);
    }
}
