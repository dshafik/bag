<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Casts\CastsPropertyGet;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class CastOutput
{
    protected array $parameters = [];

    public function __construct(protected string $casterClassname, mixed ...$parameters)
    {
        $this->parameters = $parameters;
    }

    public function transform(string $propertyName, Collection $properties): mixed
    {
        /** @var CastsPropertyGet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        return $cast->get($propertyName, $properties);
    }
}
