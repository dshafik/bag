<?php

declare(strict_types=1);

namespace Bag\Property;

use Bag\Attributes\Validation\Rule;
use Bag\Internal\Reflection;
use Illuminate\Support\Collection;
use ReflectionAttribute;

/**
 * @extends Collection<string, mixed>
 */
class ValidatorCollection extends Collection
{
    public static function create(\ReflectionParameter|\ReflectionProperty $property): self
    {
        $validators = collect(Reflection::getAttributes($property, Rule::class, ReflectionAttribute::IS_INSTANCEOF))->map(function ($attribute) {
            /** @var ReflectionAttribute<Rule> $attribute */
            return Reflection::getAttributeInstance($attribute)?->rule;
        });

        return new self($validators);
    }
}
