<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Casts\CastsPropertySet;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class CastInput
{
    protected array $parameters = [];

    public function __construct(protected string $casterClassname, mixed ...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param  class-string<CastsPropertySet>  $propertyName
     */
    public function cast(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        /** @var CastsPropertySet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        return $cast->set($propertyType, $propertyName, $properties);
    }
}
