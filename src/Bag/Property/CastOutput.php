<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\Cast;
use Bag\Attributes\CastOutput as CastOutputAttribute;
use Bag\Casts\CastsPropertyGet;
use Bag\Util;
use Illuminate\Support\Collection;
use ReflectionAttribute;

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

        /** @var array<ReflectionAttribute<Cast>> $casts */
        $casts = $property->getAttributes(Cast::class);
        if (count($casts) > 0) {
            $caster = $casts[0]->newInstance();
            $args = $casts[0]->getArguments();
            $casterClass = $args[\array_key_first($args)];
            if (\is_a($casterClass, CastsPropertyGet::class, true)) {
                $cast = $caster;
            }
        }

        /** @var array<ReflectionAttribute<CastOutputAttribute>> $casts */
        $casts = $property->getAttributes(CastOutputAttribute::class);
        if (count($casts) > 0) {
            $cast = $casts[0]->newInstance();
        }

        $name = $property->getName();
        $type = Util::getPropertyType($property);

        return new self(propertyType: $type->getName(), name: $name, caster: $cast);
    }

    public function __invoke(Collection $properties): mixed
    {
        if ($this->caster === null) {
            return $properties->get($this->name);
        }

        return $this->caster->transform(propertyName: $this->name, properties: $properties);
    }
}
