<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\Cast;
use Bag\Attributes\CastInput as CastInputAttribute;
use Bag\Casts\CastsPropertySet;
use Bag\Casts\MagicCast;
use Bag\Util;
use Illuminate\Support\Collection;
use ReflectionAttribute;

class CastInput
{
    public function __construct(
        protected string $propertyType,
        protected null|Cast|CastInputAttribute $caster
    ) {
    }

    public static function create(\ReflectionParameter|\ReflectionProperty $property): self
    {
        $cast = new CastInputAttribute(MagicCast::class);

        /** @var array<ReflectionAttribute<Cast>> $casts */
        $casts = $property->getAttributes(Cast::class);
        if (count($casts) > 0) {
            $caster = $casts[0]->newInstance();
            $args = $casts[0]->getArguments();
            $casterClass = $args[\array_key_first($args)];
            if (\is_a($casterClass, CastsPropertySet::class, true)) {
                $cast = $caster;
            }
        }

        /** @var array<ReflectionAttribute<CastInputAttribute>> $casts */
        $casts = $property->getAttributes(CastInputAttribute::class);
        if (count($casts) > 0) {
            $cast = $casts[0]->newInstance();
        }

        $type = Util::getPropertyType($property);

        return new self(propertyType: $type->getName(), caster: $cast);
    }

    public function __invoke(string $propertyName, Collection $properties): mixed
    {
        return $this->caster->cast(propertyType: $this->propertyType, propertyName: $propertyName, properties: $properties);
    }
}
