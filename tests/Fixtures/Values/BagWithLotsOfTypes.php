<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Cast;
use Bag\Attributes\MapName;
use Bag\Bag;
use Bag\Casts\CollectionOf;
use Bag\Collection;
use Bag\Mappers\SnakeCase;
use Brick\Money\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection as LaravelCollection;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Collections\ExtendsBagWithCollectionCollection;
use Tests\Fixtures\Enums\TestBackedEnum;
use Tests\Fixtures\Enums\TestUnitEnum;
use Tests\Fixtures\Models\TestModel;
use WeakMap;

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
        public LaravelCollection $collection,
        public TestBackedEnum $backed_enum,
        public TestUnitEnum $unit_enum,
        public Money $money,
        public CarbonImmutable $date_time,
        public TestModel $model,
        public ?string $nullable_string,
        public ?int $nullable_int,
        public ?bool $nullable_bool,
        public ?float $nullable_float,
        public ?array $nullable_array,
        public ?object $nullable_object,
        public ?TestBag $nullable_bag,
        public ?LaravelCollection $nullable_collection,
        public ?TestBackedEnum $nullable_backed_enum,
        public ?TestUnitEnum $nullable_unit_enum,
        public ?Money $nullable_money,
        public ?CarbonImmutable $nullable_date_time,
        public ?TestModel $nullable_model,
        public ?string $optional_string = 'optional',
        public ?int $optional_int = 100,
        public ?bool $optional_bool = true,
        public ?float $optional_float = 100.2,
        public ?array $optional_array = ['optional'],
        public ?object $optional_object = new WeakMap(),
        public mixed $optional_mixed = new WeakMap(),
        public ?TestBag $optional_bag = new ExtendsTestBag('Davey Shafik', 40, 'davey@php.net'),
        public ?LaravelCollection $optional_collection = new Collection(),
        #[Cast(CollectionOf::class, TestBag::class)]
        public ?BagWithCollectionCollection  $optional_custom_collection = new ExtendsBagWithCollectionCollection(),
        public ?TestBackedEnum $optional_backed_enum = TestBackedEnum::TEST_VALUE,
        public ?TestUnitEnum $optional_unit_enum = TestUnitEnum::TEST_VALUE,
        public ?CarbonImmutable $optional_date_time = new CarbonImmutable('1984-05-31 00:00:00'),
    ) {
    }
}
