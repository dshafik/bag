<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\Cast;
use Bag\Attributes\CastOutput as CastOutputAttribute;
use Bag\Casts\CastsPropertyGet;
use Bag\Internal\Reflection;
use Bag\Internal\Util;
use Illuminate\Support\Collection as LaravelCollection;

class CastOutput
{
    public function __construct(
        protected string $propertyType,
        protected string $name,
        protected null|Cast|CastOutputAttribute $caster
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
            if ((is_string($casterClass) || is_object($casterClass)) && !\is_a($casterClass, CastsPropertyGet::class, true)) {
                $cast = null;
            }
        }

        $castAttribute = Reflection::getAttribute($property, CastOutputAttribute::class);
        if ($castAttribute !== null) {
            $cast = Reflection::getAttributeInstance($castAttribute); // @codeCoverageIgnore
        }

        $name = $property->getName();
        /** @var string $type */
        $type = Util::getPropertyTypes($property)->first();

        /** @var CastOutputAttribute|null $cast */
        return new self(propertyType: $type, name: $name, caster: $cast);
    }

    /**
     * @param LaravelCollection<array-key,mixed> $properties
     */
    public function __invoke(LaravelCollection $properties): mixed
    {
        if ($this->caster === null) {
            return $properties->get($this->name);
        }

        return $this->caster->transform(propertyName: $this->name, properties: $properties);
    }
}
