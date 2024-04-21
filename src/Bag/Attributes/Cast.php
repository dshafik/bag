<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Casts\CastsPropertyGet;
use Bag\Casts\CastsPropertySet;
use Bag\Casts\MagicCast;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Cast
{
    protected array $parameters = [];

    public function __construct(protected string $casterClassname, mixed ...$parameters)
    {
        $this->parameters = $parameters;
    }

    public function cast(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        /** @var CastsPropertySet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        if ($cast instanceof CastsPropertySet) {
            return $cast->set($propertyType, $propertyName, $properties);
        }

        return (new MagicCast())->set($propertyName, $propertyName, $properties);
    }

    public function transform(string $propertyName, Collection $properties): mixed
    {
        /** @var CastsPropertyGet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        if ($cast instanceof CastsPropertyGet) {
            return $cast->get($propertyName, $properties);
        }

        return $properties->get($propertyName);
    }
}
