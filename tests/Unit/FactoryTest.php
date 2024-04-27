<?php

declare(strict_types=1);

use Bag\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Fixtures\BagWithFactory;
use Tests\Fixtures\BagWithFactoryAndCollection;
use Tests\Fixtures\Collections\BagWithFactoryAndCollectionCollection;
use Tests\Fixtures\Factories\BagWithFactoryFactory;

uses(WithFaker::class);

it('resolves factory', function () {
    expect(BagWithFactory::factory())->toBeInstanceOf(BagWithFactoryFactory::class);
});

it('makes with factory default state', function () {
    $bag = BagWithFactory::factory()->make();

    expect($bag)->toBeInstanceOf(BagWithFactory::class);
    expect($bag->name)->toBe('Davey Shafik');
    expect($bag->age)->toBe(40);
});

it('makes multiple using count with default state', function () {
    $bags = BagWithFactory::factory()->count(3)->make();

    expect($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactory $bag) {
        expect($bag->name)->toBe('Davey Shafik');
        expect($bag->age)->toBe(40);
    });
});

it('makes multiple using factory with default state', function () {
    $bags = BagWithFactory::factory(3)->make();

    expect($bags)->toBeInstanceOf(Collection::class);
    expect($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactory $bag) {
        expect($bag->name)->toBe('Davey Shafik');
        expect($bag->age)->toBe(40);
    });
});

it('makes multiple with sequences', function () {
    $data = [
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
    ];

    $bags = BagWithFactory::factory()->count(3)->state(new Sequence(... $data))->make();

    expect($bags)->toBeInstanceOf(Collection::class);
    expect($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactory $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name']);
        expect($bag->age)->toBe($data[$index]['age']);
    });
});

it('makes multiple with sequences and wraps around', function () {
    $data = [
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
    ];

    $bags = BagWithFactory::factory()->count(6)->state(new Sequence(... $data))->make();

    $data = array_merge($data, $data);

    expect($bags)->toBeInstanceOf(Collection::class);
    expect($bags)->toHaveCount(6);
    $bags->each(function (BagWithFactory $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name']);
        expect($bag->age)->toBe($data[$index]['age']);
    });
});

it('uses custom bag collection', function () {
    $bags = BagWithFactoryAndCollection::factory()->count(3)->make();

    expect($bags)->toBeInstanceOf(BagWithFactoryAndCollectionCollection::class);
    expect($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactoryAndCollection $bag) {
        expect($bag->name)->toBe('Davey Shafik');
        expect($bag->age)->toBe(40);
    });
});
