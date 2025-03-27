<?php

declare(strict_types=1);
use Bag\Casts\CollectionOf;
use Bag\Collection;
use Bag\Exceptions\BagNotFoundException;
use Bag\Exceptions\InvalidBag;
use Bag\Exceptions\InvalidCollection;
use Illuminate\Support\Collection as LaravelCollection;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\TestBag;

covers(CollectionOf::class);

test('it creates laravel collection of bags', function () {
    $cast = new CollectionOf(TestBag::class);

    $collection = $cast->set(Collection::wrap(LaravelCollection::class), 'test', collect(['test' => [
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

    $collection = $cast->set(Collection::wrap(Collection::class), 'test', collect(['test' => [
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

    $collection = $cast->set(Collection::wrap(BagWithCollectionCollection::class), 'test', collect(['test' => [
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

    $collection = $cast->set(Collection::wrap(Collection::class), 'test', collect(['test' => [
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
    $type = Collection::wrap((new ReflectionClosure(fn (\stdClass $type) => true))->getParameters()[0]->getType());

    $cast = new CollectionOf(TestBag::class);
    $cast->set($type, 'test', collect(['test' => [
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
})->throws(InvalidCollection::class, 'The property "test" must be a subclass of Illuminate\Support\Collection');

test('it fails with invalid bag', function () {
    $type = Collection::wrap((new ReflectionClosure(fn (\stdClass $type) => true))->getParameters()[0]->getType());

    $cast = new CollectionOf(\stdClass::class);
    $cast->set($type, 'test', collect(['test' => [
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
})->throws(InvalidBag::class, 'CollectionOf class "stdClass" must extend Bag\Bag');

test('it fails with non existent bag', function () {
    $type = Collection::wrap((new ReflectionClosure(fn (\stdClass $type) => true))->getParameters()[0]->getType());

    $cast = new CollectionOf('test-string');
    $cast->set($type, 'test', collect(['test' => [
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
})->throws(BagNotFoundException::class, 'The Bag class "test-string" does not exist');
