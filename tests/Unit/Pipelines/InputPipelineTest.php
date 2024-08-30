<?php

declare(strict_types=1);
use Bag\Pipelines\InputPipeline;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\TestBag;

test('it creates bag', function () {
    $input = new BagInput(TestBag::class, [
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $bag = InputPipeline::process($input);

    expect($bag)->toBeInstanceOf(TestBag::class);
});
