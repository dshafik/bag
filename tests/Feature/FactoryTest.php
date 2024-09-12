<?php

declare(strict_types=1);

use Bag\Attributes\Factory as FactoryAttribute;
use Bag\Collection;
use Bag\Exceptions\MissingFactoryException;
use Bag\Factory;
use Bag\Traits\HasFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\Fixtures\Collections\BagWithFactoryAndCollectionCollection;
use Tests\Fixtures\Factories\BagWithFactoryFactory;
use Tests\Fixtures\Values\BagWithFactory;
use Tests\Fixtures\Values\BagWithFactoryAndCollection;
use Tests\Fixtures\Values\BagWithInvalidFactoryAttribute;
use Tests\Fixtures\Values\BagWithoutFactoryAttribute;

covers(Factory::class, HasFactory::class, FactoryAttribute::class);

test('it resolves factory', function () {
    expect(BagWithFactory::factory())->toBeInstanceOf(BagWithFactoryFactory::class);
});

test('it resolves factory using cache', function () {
    expect(BagWithFactory::factory())->toBeInstanceOf(BagWithFactoryFactory::class)
        ->and(BagWithFactory::factory())->toBeInstanceOf(BagWithFactoryFactory::class);
});

test('it makes with factory default state', function () {
    $bag = BagWithFactory::factory()->make();

    expect($bag)->toBeInstanceOf(BagWithFactory::class)
        ->and($bag->name)->toBe('Davey Shafik')
        ->and($bag->age)->toBe(40);
});

test('it makes multiple using count with default state', function () {
    $bags = BagWithFactory::factory()->count(3)->make();

    expect($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactory $bag) {
        expect($bag->name)->toBe('Davey Shafik')
            ->and($bag->age)->toBe(40);
    });
});

test('it makes multiple using factory with default state', function () {
    $bags = BagWithFactory::factory(3)->make();

    expect($bags)->toBeInstanceOf(Collection::class)
        ->and($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactory $bag) {
        expect($bag->name)->toBe('Davey Shafik')
            ->and($bag->age)->toBe(40);
    });
});

test('it makes multiple with sequences', function () {
    $data = [
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
    ];

    $bags = BagWithFactory::factory()->count(3)->state(new Sequence(...$data))->make();

    expect($bags)->toBeInstanceOf(Collection::class)
        ->and($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactory $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name'])
            ->and($bag->age)->toBe($data[$index]['age']);
    });
});

test('it makes multiple with sequences and wraps around', function () {
    $data = [
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
    ];

    $bags = BagWithFactory::factory()->count(6)->state(new Sequence(...$data))->make();

    $data = array_merge($data, $data);

    expect($bags)->toBeInstanceOf(Collection::class)
        ->and($bags)->toHaveCount(6);
    $bags->each(function (BagWithFactory $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name'])
            ->and($bag->age)->toBe($data[$index]['age']);
    });
});

test('it uses custom bag collection', function () {
    $bags = BagWithFactoryAndCollection::factory()->count(3)->make();

    expect($bags)->toBeInstanceOf(BagWithFactoryAndCollectionCollection::class)
        ->and($bags)->toHaveCount(3);
    $bags->each(function (BagWithFactoryAndCollection $bag) {
        expect($bag->name)->toBe('Davey Shafik')
            ->and($bag->age)->toBe(40);
    });
});

test('it creates using sequence', function () {
    $data = [
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
    ];

    $bags = BagWithFactory::factory()->count(3)->sequence(... $data)->make();

    expect($bags)->toBeInstanceOf(Collection::class)
        ->and($bags[0]->toArray())->toBe($data[0])
        ->and($bags[1]->toArray())->toBe($data[1])
        ->and($bags[2]->toArray())->toBe($data[0]);
});

test('it errors without factory attribute', function () {
    $this->expectException(MissingFactoryException::class);
    $this->expectExceptionMessage('Bag "Tests\Fixtures\Values\BagWithoutFactoryAttribute" missing factory attribute');

    BagWithoutFactoryAttribute::factory();
});

test('it errors with invalid factory attribute', function () {
    $this->expectException(MissingFactoryException::class);
    $this->expectExceptionMessage('Factory class "InvalidFactoryClass" for Bag "Tests\Fixtures\Values\BagWithInvalidFactoryAttribute" not found');

    BagWithInvalidFactoryAttribute::factory();
});
