<?php

declare(strict_types=1);
use Bag\Enums\OutputType;
use Bag\Pipelines\OutputPipeline;
use Bag\Pipelines\Values\BagOutput;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\WrappedBag;
use Tests\Fixtures\Values\WrappedBothBag;
use Tests\Fixtures\Values\WrappedJsonBag;

covers(BagOutput::class, OutputPipeline::class);

test('it get array', function () {
    $bag = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);
});

test('it get array wrapped', function () {
    $bag = WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ]);
});

test('it gets json', function () {
    $bag = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $output = new BagOutput($bag, OutputType::JSON);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);
});

test('it get json wrapped', function () {
    $bag = WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::JSON);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ]);
});

test('it gets both wrapped', function () {
    $bag = WrappedBothBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ]);

    $output = new BagOutput($bag, OutputType::JSON);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'json_wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ]);
});

test('it get json only wrapped', function () {
    $bag = WrappedJsonBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::JSON);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ]);
});

test('it gets unwrapped', function () {
    $bag = WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::UNWRAPPED);

    $result = OutputPipeline::process($output);

    expect($result)
        ->toBeArray()
        ->toBe([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);
});
