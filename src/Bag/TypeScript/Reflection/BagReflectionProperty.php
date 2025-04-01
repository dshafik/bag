<?php

declare(strict_types=1);

namespace Bag\TypeScript\Reflection;

use ReflectionAttribute;
use ReflectionNamedType;
use ReflectionObject;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;
use Spatie\TypeScriptTransformer\Attributes\Optional;

class BagReflectionProperty extends ReflectionProperty
{
    /**
     * @var ReflectionAttribute<Optional>|null
     */
    #[Optional]
    protected ?ReflectionAttribute $optional = null;

    public function __construct(object|string $class, string $property)
    {
        parent::__construct($class, $property);

        $this->optional = (new ReflectionObject($this))->getProperty('optional')->getAttributes(Optional::class)[0];
    }

    public function getType(): ?ReflectionType
    {
        $type = parent::getType();
        if ($type instanceof ReflectionUnionType) {
            return new BagReflectionUnionType($type);
        }

        return $type;
    }

    public function getAttributes(?string $name = null, int $flags = 0): array
    {
        $attributes = parent::getAttributes($name, $flags);

        if ($name !== null && $name !== Optional::class) {
            return $attributes;
        }

        $type = parent::getType();
        if ($type instanceof ReflectionUnionType && collect($type->getTypes())->contains(function (ReflectionNamedType $type) {
            return $type->getName() === \Bag\Values\Optional::class;
        })) {
            $attributes[] = $this->optional;
        }

        return $attributes;
    }
}
