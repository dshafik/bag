<?php

declare(strict_types=1);

use Bag\Attributes\Collection as CollectionAttribute;
use Bag\Collection;
use Bag\Concerns\WithCollections;
use Bag\Internal\Cache;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\Fixtures\Values\TestBag;

covers(WithCollections::class, Collection::class, CollectionAttribute::class);

test('it creates custom collections', function () {
    $data = [
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
    ];

    $collection = BagWithCollection::collect($data);

    expect($collection)->toBeInstanceOf(BagWithCollectionCollection::class)
        ->and($collection)->toHaveCount(2);

    $collection->each(function (BagWithCollection $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name'])
            ->and($bag->age)->toBe($data[$index]['age']);
    });
});

test('it uses cache', function () {
    Cache::fake()->shouldReceive('store')->atLeast()->twice()->passthru();

    $data = [
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
    ];

    $collection = BagWithCollection::collect($data);

    expect($collection)->toBeInstanceOf(BagWithCollectionCollection::class)
        ->and($collection)->toHaveCount(2);
    $collection->each(function (BagWithCollection $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name'])
            ->and($bag->age)->toBe($data[$index]['age']);
    });

    $collection = BagWithCollection::collect($data);

    expect($collection)->toBeInstanceOf(BagWithCollectionCollection::class)
        ->and($collection)->toHaveCount(2);
    $collection->each(function (BagWithCollection $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name'])
            ->and($bag->age)->toBe($data[$index]['age']);
    });
});

test('it can be cast to a collection', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ])->toCollection();

    expect($value->toArray())->toBe(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);
});
