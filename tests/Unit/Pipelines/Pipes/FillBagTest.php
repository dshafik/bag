<?php

declare(strict_types=1);
use Bag\Pipelines\Pipes\FillBag;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\TestBag;

covers(FillBag::class);

test('it creates bag instance', function () {
    $input = new BagInput(TestBag::class, collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);

    $pipe = new FillBag();
    $input = $pipe($input);

    expect($input->bag)->toBeInstanceOf(TestBag::class);
});
