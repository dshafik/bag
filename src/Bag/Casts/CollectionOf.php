<?php

declare(strict_types=1);

namespace Bag\Casts;

use Attribute;
use Bag\Bag;
use Bag\Exceptions\InvalidCollection;
use Illuminate\Support\Collection;
use Override;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class CollectionOf implements CastsPropertySet
{
    /**
     * @param  class-string<Bag>  $valueClassname
     */
    public function __construct(public string $valueClassname)
    {
    }

    /**
     * @param  class-string<Collection>  $propertyType
     */
    #[Override]
    public function set(string $propertyType, string $propertyName, Collection $properties): mixed
    {
        if ($propertyType !== Collection::class && ! \is_subclass_of($propertyType, Collection::class, true)) {
            throw new InvalidCollection(sprintf('The property %s->%s must be a subclass of Collection', $propertyType, $propertyName));
        }

        return $propertyType::make($properties->get($propertyName))->map(fn ($item) => $this->valueClassname::from($item));
    }
}
