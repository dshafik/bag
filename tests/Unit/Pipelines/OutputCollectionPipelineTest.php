<?php

declare(strict_types=1);
use Bag\Enums\OutputType;
use Bag\Pipelines\OutputCollectionPipeline;
use Bag\Pipelines\Values\CollectionOutput;
use Tests\Fixtures\Collections\WrappedCollection;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\WrappedBag;
use Tests\Fixtures\Values\WrappedJsonBag;

covers(OutputCollectionPipeline::class);

test('it get array', function () {
    $collection = collect([TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ])]);

    $output = new CollectionOutput($collection, OutputType::ARRAY);

    $result = OutputCollectionPipeline::process($output);

    expect($result->toArray())->toBe([
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]
    ]);
});

test('it get array wrapped', function () {
    $collection = WrappedCollection::make([WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ])]);

    $output = new CollectionOutput($collection, OutputType::ARRAY);

    $result = OutputCollectionPipeline::process($output);

    expect($result->toArray())->toBe([
        'collection_wrapper' => [
            [
                'wrapper' => [
                    'name' => 'Davey Shafik',
                    'age' => 40,
                ]
            ]
        ]
    ]);
});

test('it get json', function () {
    $collection = collect([TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ])]);

    $output = new CollectionOutput($collection, OutputType::JSON);

    $result = OutputCollectionPipeline::process($output);

    expect($result->toArray())->toBe([
        [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]
    ]);
});

test('it get json wrapped', function () {
    $collection = WrappedCollection::make([WrappedJsonBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ])]);

    $output = new CollectionOutput($collection, OutputType::JSON);

    $result = OutputCollectionPipeline::process($output);

    expect($result->jsonSerialize())->toBe([
        'collection_json_wrapper' => [
            [
                'wrapper' => [
                    'name' => 'Davey Shafik',
                    'age' => 40,
                ]
            ]
        ]
    ]);
});

test('it gets unwrapped', function () {
    $collection = WrappedCollection::make([WrappedJsonBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ])]);

    $output = new CollectionOutput($collection, OutputType::UNWRAPPED);

    $result = OutputCollectionPipeline::process($output);

    expect($result->toArray())->toBe([
        [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]
    ]);
});
