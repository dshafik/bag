<?php

declare(strict_types=1);
use Bag\Casts\CollectionOf;
use Bag\Collection;
use Bag\Exceptions\BagNotFoundException;
use Bag\Exceptions\InvalidBag;
use Bag\Exceptions\InvalidCollection;
use Illuminate\Support\Collection as LaravelCollection;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\TestBag;

test('it creates laravel collection of bags', function () {
    $cast = new CollectionOf(TestBag::class);
    $collection = $cast->set(LaravelCollection::class, 'test', collect(['test' => [
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ],
        [
            'name' => 'David Shafik',
            'age' => 40,
            'email' => 'david@example.org',
        ],
    ]]));

    expect($collection)
        ->toBeInstanceOf(LaravelCollection::class)
        ->toContainOnlyInstancesOf(TestBag::class)
        ->and($collection->toArray())->toBe([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ]);

});

test('it creates collection of bags', function () {
    $cast = new CollectionOf(TestBag::class);
    $collection = $cast->set(Collection::class, 'test', collect(['test' => [
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ],
        [
            'name' => 'David Shafik',
            'age' => 40,
            'email' => 'david@example.org',
        ],
    ]]));

    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->toContainOnlyInstancesOf(TestBag::class)
        ->and($collection->toArray())->toBe([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ]);

});

test('it creates custom collection of bags', function () {
    $cast = new CollectionOf(TestBag::class);
    $collection = $cast->set(BagWithCollectionCollection::class, 'test', collect(['test' => [
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ],
        [
            'name' => 'David Shafik',
            'age' => 40,
            'email' => 'david@example.org',
        ],
    ]]));

    expect($collection)
        ->toBeInstanceOf(BagWithCollectionCollection::class)
        ->toContainOnlyInstancesOf(TestBag::class)
        ->and($collection->toArray())->toBe([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ]);

});

test('it creates collection using existing bags', function () {
    $cast = new CollectionOf(TestBag::class);
    $collection = $cast->set(Collection::class, 'test', collect(['test' => [
        TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ])
    ]]));

    expect($collection)
        ->toBeInstanceOf(Collection::class)
        ->toContainOnlyInstancesOf(TestBag::class);
});

test('it fails with invalid collection', function () {
    $this->expectException(InvalidCollection::class);
    $this->expectExceptionMessage('The property "test" must be a subclass of Illuminate\Support\Collection');

    $cast = new CollectionOf(TestBag::class);
    $cast->set(\stdClass::class, 'test', collect(['test' => [
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ],
        [
            'name' => 'David Shafik',
            'age' => 40,
            'email' => 'david@example.org',
        ],
    ]]));
});

test('it fails with invalid bag', function () {
    $this->expectException(InvalidBag::class);
    $this->expectExceptionMessage('CollectionOf class "P\Tests\Unit\Casts\CollectionOfTest" must extend Bag\Bag');

    $cast = new CollectionOf(static::class);
    $cast->set(\stdClass::class, 'test', collect(['test' => [
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ],
        [
            'name' => 'David Shafik',
            'age' => 40,
            'email' => 'david@example.org',
        ],
    ]]));
});

test('it fails with non existent bag', function () {
    $this->expectException(BagNotFoundException::class);
    $this->expectExceptionMessage('The Bag class "test-string" does not exist');

    $cast = new CollectionOf('test-string');
    $cast->set(\stdClass::class, 'test', collect(['test' => [
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ],
        [
            'name' => 'David Shafik',
            'age' => 40,
            'email' => 'david@example.org',
        ],
    ]]));
});
