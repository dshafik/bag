<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Cast;
use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Casts\CollectionOf;
use Bag\Mappers\SnakeCase;
use Illuminate\Support\Collection;
use Tests\Fixtures\Collections\BagWithCollectionCollection;

#[MapName(SnakeCase::class)]
readonly class BagWithLotsOfTypes extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
        public bool $is_active,
        public float $price,
        public array $items,
        public object $object,
        public mixed $mixed,
        public TestBag $bag,
        public Collection $collection,
        public ?string $nullable_string,
        public ?int $nullable_int,
        public ?bool $nullable_bool,
        public ?float $nullable_float,
        public ?array $nullable_array,
        public ?object $nullable_object,
        public ?TestBag $nullable_bag,
        public ?Collection $nullable_collection,
        public ?string $optional_string = null,
        public ?int $optional_int = null,
        public ?bool $optional_bool = null,
        public ?float $optional_float = null,
        public ?array $optional_array = null,
        public ?object $optional_object = null,
        public mixed $optional_mixed = null,
        public ?TestBag $optional_bag = null,
        public ?Collection $optional_collection = null,
        #[Cast(CollectionOf::class, TestBag::class)]
        public ?BagWithCollectionCollection  $optional_custom_collection = null,
    ) {
    }
}
