<?php

declare(strict_types=1);
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\WrapCollection;
use Bag\Pipelines\Values\CollectionOutput;
use Tests\Fixtures\Collections\WrappedCollection;
use Tests\Fixtures\Values\TestBag;

covers(WrapCollection::class, CollectionOutput::class);

test('it does not wrap collection with no wrapper', function () {
    $collection = TestBag::collect([[
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]]);

    $output = new CollectionOutput($collection, OutputType::ARRAY);

    $pipe = new WrapCollection();
    $output = $pipe($output);

    expect($output->collection->toArray())->toBe([
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]
    ]);
});

test('it does not wrap collection unwrapped output', function () {
    $collection = TestBag::collect([[
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]]);

    $output = new CollectionOutput($collection, OutputType::UNWRAPPED);

    $pipe = new WrapCollection();
    $output = $pipe($output);

    expect($output->collection->toArray())->toBe([
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]
    ]);
});

test('it wraps collection array', function () {
    $collection = WrappedCollection::make([
        TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ])
    ]);

    $output = new CollectionOutput($collection, OutputType::ARRAY);

    $pipe = new WrapCollection();
    $output = $pipe($output);

    expect($output->collection->toArray())->toBe([
        'collection_wrapper' => [
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ]
        ]
    ]);
});

test('it wraps collection json', function () {
    $collection = WrappedCollection::make([
        TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ])
    ]);

    $output = new CollectionOutput($collection, OutputType::JSON);

    $pipe = new WrapCollection();
    $output = $pipe($output);

    expect($output->collection->toArray())->toBe([
        'collection_json_wrapper' => [
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ]
        ]
    ]);
});
