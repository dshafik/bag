<?php

declare(strict_types=1);

namespace Bag\Casts;

use Bag\Bag;
use Bag\Collection;
use Bag\Exceptions\BagNotFoundException;
use Bag\Exceptions\InvalidBag;
use Bag\Exceptions\InvalidCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as LaravelCollection;
use Override;
use ReflectionNamedType;

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
     * @param Collection<ReflectionNamedType> $propertyTypes
     * @param LaravelCollection<array-key,array<array-key, Arrayable<(int|string), mixed>|iterable<(int|string), mixed>|null>> $properties
     */
    #[Override]
    public function set(Collection $propertyTypes, string $propertyName, LaravelCollection $properties): mixed
    {
        /** @var class-string<LaravelCollection<array-key,mixed>> $propertyType */
        $propertyType = $propertyTypes->firstWhere(fn ($type) => $type === LaravelCollection::class || \is_subclass_of($type, LaravelCollection::class, true));

        if ($propertyType === null) {
            throw new InvalidCollection(sprintf('The property "%s" must be a subclass of %s', $propertyName, LaravelCollection::class));
        }

        return $propertyType::make($properties->get($propertyName))->map(function (mixed $item) {
            if ($item instanceof Bag && $item::class === $this->valueClassname) {
                return clone $item;
            }

            return $this->valueClassname::from($item);
        });
    }
}
