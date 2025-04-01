<?php

declare(strict_types=1);

namespace Bag\TypeScript\Reflection;

use Bag\Values\Optional;
use Illuminate\Support\Traits\ForwardsCalls;
use ReflectionNamedType;
use ReflectionUnionType;

class BagReflectionUnionType extends ReflectionUnionType
{
    use ForwardsCalls;

    public function __construct(protected ReflectionUnionType $type)
    {
    }

    public function getTypes(): array
    {
        return collect($this->type->getTypes())->filter(function (ReflectionNamedType $type) {
            return $type->getName() !== Optional::class;
        })->toArray();
    }

    public function allowsNull(): bool
    {
        return $this->forwardCallTo($this->type, 'allowsNull', []);
    }

    public function isBuiltin()
    {
        return $this->forwardCallTo($this->type, 'isBuiltin', []);
    }

    public function __toString(): string
    {
        return $this->forwardCallTo($this->type, '__toString', []);
    }
}
