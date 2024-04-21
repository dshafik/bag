<?php

declare(strict_types=1);

namespace Bag\Casts;

use Attribute;
use Bag\Bag;
use Illuminate\Support\Collection;
use Override;
use RuntimeException;

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
     * @param  class-string<Collection>  $type
     */
    #[Override]
    public function set(string $type, string $propertyName, Collection $properties): mixed
    {
        if ($type !== Collection::class && ! \is_subclass_of($type, Collection::class, true)) {
            throw new RuntimeException("The property {$propertyName} must be a Collection or a subclass of Collection");
        }

        return $type::make($properties->get($propertyName))->map(fn ($item) => $this->valueClassname::from($item));
    }
}
