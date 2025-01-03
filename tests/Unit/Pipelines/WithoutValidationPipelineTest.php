<?php

declare(strict_types=1);
use Bag\Pipelines\Values\BagInput;
use Bag\Pipelines\WithoutValidationPipeline;
use Tests\Fixtures\Values\OptionalPropertiesBag;
use Tests\Fixtures\Values\TestBag;

covers(BagInput::class, WithoutValidationPipeline::class);

test('it creates bag', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]));

    $bag = WithoutValidationPipeline::process($input);

    expect($bag)->toBeInstanceOf(TestBag::class);
});

test('it creates invalid bag', function () {
    $input = new BagInput(OptionalPropertiesBag::class, collect([
        'name' => 'Davey Shafik',
    ]));

    $bag = WithoutValidationPipeline::process($input);

    expect($bag)
        ->toBeInstanceOf(OptionalPropertiesBag::class)
    ->and($bag->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => null,
        'email' => null,
        'bag' => null,
    ]);
});
