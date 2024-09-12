<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Casts\CastsPropertyGet;
use Bag\Casts\CastsPropertySet;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Cast
{
    protected array $parameters = [];

    /**
     * @param class-string<CastsPropertyGet>|class-string<CastsPropertySet> $casterClassname
     */
    public function __construct(protected string $casterClassname, mixed ...$parameters)
    {
        $this->parameters = $parameters;
    }

    public function cast(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        /** @var CastsPropertySet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        return $cast->set($propertyType, $propertyName, $properties);
    }

    public function transform(string $propertyName, Collection $properties): mixed
    {
        /** @var CastsPropertyGet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        return $cast->get($propertyName, $properties);
    }
}
