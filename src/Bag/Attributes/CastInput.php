<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;
use Bag\Casts\CastsPropertySet;
use Bag\Collection;
use Illuminate\Support\Collection as LaravelCollection;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class CastInput implements AttributeInterface
{
    /**
     * @var array<array-key,mixed>
     */
    protected array $parameters = [];

    public function __construct(protected string $casterClassname, mixed ...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param class-string<CastsPropertySet> $propertyName
     * @param LaravelCollection<array-key,mixed> $properties
     */
    public function cast(Collection $propertyType, string $propertyName, LaravelCollection $properties): mixed
    {
        /** @var CastsPropertySet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        return $cast->set($propertyType, $propertyName, $properties);
    }
}
