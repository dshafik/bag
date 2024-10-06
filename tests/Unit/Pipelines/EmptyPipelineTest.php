<?php

declare(strict_types=1);

use Bag\Pipelines\EmptyPipeline;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Support\Collection;
use Tests\Fixtures\Values\TestBag;

covers(BagInput::class, EmptyPipeline::class);

test('it creates empty bag', function () {
    $input = new BagInput(TestBag::class, Collection::empty());

    $bag = EmptyPipeline::process($input);

    expect($bag)
        ->toBeInstanceOf(TestBag::class)
        ->and($bag->toArray())->toBe(['name' => '', 'age' => 0,  'email' => '']);
});

test('it creates partial bag', function () {
    $input = new BagInput(TestBag::class, collect(['email' => 'davey@php.net']));

    $bag = EmptyPipeline::process($input);

    expect($bag)
        ->toBeInstanceOf(TestBag::class)
        ->and($bag->toArray())->toBe(['name' => '', 'age' => 0,  'email' => 'davey@php.net']);
});
