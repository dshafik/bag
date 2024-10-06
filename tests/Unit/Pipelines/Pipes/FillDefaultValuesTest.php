<?php

declare(strict_types=1);
use Bag\Collection;
use Bag\Pipelines\Pipes\FillBag;
use Bag\Pipelines\Pipes\FillDefaultValues;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Brick\Money\Money;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection as LaravelCollection;
use Tests\Fixtures\Collections\ExtendsBagWithCollectionCollection;
use Tests\Fixtures\Enums\TestBackedEnum;
use Tests\Fixtures\Enums\TestUnitEnum;
use Tests\Fixtures\Models\TestModel;
use Tests\Fixtures\Values\BagWithLotsOfTypes;
use Tests\Fixtures\Values\ExtendsTestBag;
use Tests\Fixtures\Values\TestBag;

covers(FillDefaultValues::class);

test('it creates empty bag instance', function () {
    $input = new BagInput(BagWithLotsOfTypes::class, LaravelCollection::empty());
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);

    $pipe = new FillDefaultValues();
    $input = $pipe($input);

    $input = (new FillBag())($input);

    /** @var BagWithLotsOfTypes $bag */
    $bag = $input->bag;

    expect($bag)
        ->toBeInstanceOf(BagWithLotsOfTypes::class)
        ->and($bag->name)->toBe('')
        ->and($bag->age)->toBe(0)
        ->and($bag->is_active)->toBeFalse()
        ->and($bag->price)->toBe(0.0)
        ->and($bag->items)->toBe([])
        ->and($bag->object)->toBeInstanceOf(\stdClass::class)
        ->and($bag->mixed)->toBeNull()
        ->and($bag->bag)->toBeInstanceOf(TestBag::class)
        ->and($bag->bag->name)->toBe('')
        ->and($bag->bag->age)->toBe(0)
        ->and($bag->bag->email)->toBe('')
        ->and($bag->collection)->toBeInstanceOf(LaravelCollection::class)
        ->and($bag->collection->isEmpty())->toBeTrue()
        ->and($bag->backed_enum)->toBe(TestBackedEnum::TEST_VALUE)
        ->and($bag->unit_enum)->toBe(TestUnitEnum::TEST_VALUE)
        ->and($bag->money)->toBeInstanceOf(Money::class)
        ->and($bag->money->isZero())->toBeTrue()
        ->and($bag->money->getCurrency()->getCurrencyCode())->toBe(\NumberFormatter::create(\Locale::getDefault(), \NumberFormatter::CURRENCY)->getTextAttribute(\NumberFormatter::CURRENCY_CODE))
        ->and($bag->date_time)->toBeInstanceOf(CarbonImmutable::class)
        ->and($bag->date_time->equalTo(new CarbonImmutable('1970-01-01 00:00:00')))->toBeTrue()
        ->and($bag->model)->toBeInstanceOf(TestModel::class)
        ->and($bag->model->toArray())->toBe(TestModel::make()->toArray())
        ->and($bag->nullable_string)->toBeNull()
        ->and($bag->nullable_int)->toBeNull()
        ->and($bag->nullable_bool)->toBeNull()
        ->and($bag->nullable_float)->toBeNull()
        ->and($bag->nullable_array)->toBeNull()
        ->and($bag->nullable_object)->toBeNull()
        ->and($bag->nullable_bag)->toBeNull()
        ->and($bag->nullable_collection)->toBeNull()
        ->and($bag->nullable_backed_enum)->toBeNull()
        ->and($bag->nullable_unit_enum)->toBeNull()
        ->and($bag->nullable_money)->toBeNull()
        ->and($bag->nullable_date_time)->toBeNull()
        ->and($bag->nullable_model)->toBeNull()
        ->and($bag->optional_string)->toBe('optional')
        ->and($bag->optional_int)->toBe(100)
        ->and($bag->optional_bool)->toBeTrue()
        ->and($bag->optional_float)->toBe(100.2)
        ->and($bag->optional_array)->toBe(['optional'])
        ->and($bag->optional_object)->toBeInstanceOf(\WeakMap::class)
        ->and($bag->optional_mixed)->toBeInstanceOf(\WeakMap::class)
        ->and($bag->optional_bag)->toBeInstanceOf(ExtendsTestBag::class)
        ->and($bag->optional_bag?->name)->toBe('Davey Shafik')
        ->and($bag->optional_bag?->age)->toBe(40)
        ->and($bag->optional_bag?->email)->toBe('davey@php.net')
        ->and($bag->optional_collection)->toBeInstanceOf(Collection::class)
        ->and($bag->optional_custom_collection)->toBeInstanceOf(ExtendsBagWithCollectionCollection::class)
        ->and($bag->optional_backed_enum)->toBe(TestBackedEnum::TEST_VALUE)
        ->and($bag->optional_unit_enum)->toBe(TestUnitEnum::TEST_VALUE)
        ->and($bag->optional_date_time)->toBeInstanceOf(CarbonImmutable::class)
        ->and($bag->optional_date_time->equalTo(new CarbonImmutable('1984-05-31 00:00:00')))->toBeTrue()
    ;
});

test('it creates partial bag instance', function () {
    $input = new BagInput(TestBag::class, collect(['email' => 'davey@php.net']));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);

    $pipe = new FillDefaultValues();
    $input = $pipe($input);

    $input = (new FillBag())($input);

    /** @var TestBag $bag */
    $bag = $input->bag;

    expect($bag)
        ->toBeInstanceOf(TestBag::class)
        ->and($bag->name)->toBe('')
        ->and($bag->age)->toBe(0)
        ->and($bag->email)->toBe('davey@php.net');
});
