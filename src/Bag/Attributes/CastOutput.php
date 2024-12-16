<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;
use Bag\Casts\CastsPropertyGet;
use Illuminate\Support\Collection;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class CastOutput implements AttributeInterface
{
    /**
     * @var array<array-key,mixed>
     */
    protected array $parameters;

    public function __construct(protected string $casterClassname, mixed ...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @param Collection<array-key,mixed> $properties
     */
    public function transform(string $propertyName, Collection $properties): mixed
    {
        /** @var CastsPropertyGet $cast */
        $cast = new $this->casterClassname(...$this->parameters);

        return $cast->get($propertyName, $properties);
    }
}
