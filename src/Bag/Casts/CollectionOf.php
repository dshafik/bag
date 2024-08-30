<?php

declare(strict_types=1);

namespace Bag\Casts;

use Bag\Bag;
use Bag\Exceptions\BagNotFoundException;
use Bag\Exceptions\InvalidBag;
use Bag\Exceptions\InvalidCollection;
use Illuminate\Support\Collection as LaravelCollection;
use Override;

class CollectionOf implements CastsPropertySet
{
    /**
     * @param  class-string<Bag>  $valueClassname
     */
    public function __construct(public string $valueClassname)
    {
        if (!\class_exists($this->valueClassname)) {
            throw new BagNotFoundException($this->valueClassname);
        }

        if (!\is_subclass_of($this->valueClassname, Bag::class)) {
            throw new InvalidBag(sprintf('CollectionOf class "%s" must extend %s', $this->valueClassname, Bag::class));
        }
    }

    /**
     * @param  class-string<LaravelCollection>  $propertyType
     */
    #[Override]
    public function set(string $propertyType, string $propertyName, LaravelCollection $properties): mixed
    {
        if ($propertyType !== LaravelCollection::class && ! \is_subclass_of($propertyType, LaravelCollection::class, true)) {
            throw new InvalidCollection(sprintf('The property "%s" must be a subclass of %s', $propertyName, LaravelCollection::class));
        }

        return $propertyType::make($properties->get($propertyName))->map(fn (mixed $item) => $this->valueClassname::from($item));
    }
}
