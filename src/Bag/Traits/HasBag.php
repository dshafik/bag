<?php

declare(strict_types=1);

namespace Bag\Traits;

use Bag\Attributes\Bag as BagAttribute;
use Bag\Bag;
use Bag\Collection;
use Bag\Exceptions\BagAttributeNotFoundException;
use Bag\Exceptions\BagNotFoundException;
use Bag\Internal\Reflection;
use function get_object_vars;

trait HasBag
{
    public function toBag(?int $propertyVisibility = null): Bag
    {
        $bagAttribute = Reflection::getAttributeInstance(Reflection::getClass($this), BagAttribute::class);
        if ($bagAttribute === null) {
            throw new BagAttributeNotFoundException('Bag attribute not found on class ' . static::class);
        }

        $bagClass = $bagAttribute->bagClass;

        if (!\class_exists($bagClass)) {
            throw new BagNotFoundException($bagClass);
        }

        return $bagClass::from($this->getObjectVars($propertyVisibility ?? $bagAttribute->visibility));
    }

    public function getObjectVars(int $propertyVisibility = BagAttribute::PUBLIC): Collection
    {
        $object = $this;
        $public = Collection::make((fn () => get_object_vars($object))->call(new class () { }));
        $protected = Collection::make((array) $object)
                ->filter(fn ($value, $key) => $key[0] === "\0" && $key[1] === '*' && $key[2] === "\0")
                ->mapWithKeys(fn ($value, $key) => [substr($key, 3) => $value]);
        $private = Collection::make(get_object_vars($this))
            ->except($public->keys()->merge($protected->keys()));

        $values = Collection::empty();
        if (($propertyVisibility & BagAttribute::ALL) === BagAttribute::ALL) {
            return $public->merge($protected)->merge($private);
        }

        if (($propertyVisibility & BagAttribute::PUBLIC) === BagAttribute::PUBLIC) {
            $values = $values->merge($public);
        }

        if (($propertyVisibility & BagAttribute::PROTECTED) === BagAttribute::PROTECTED) {
            $values = $values->merge($protected);
        }

        if (($propertyVisibility & BagAttribute::PRIVATE) === BagAttribute::PRIVATE) {
            $values = $values->merge($private);
        }

        return $values;
    }
}
