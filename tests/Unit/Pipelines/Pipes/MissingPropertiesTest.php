<?php

declare(strict_types=1);
use Bag\Exceptions\MissingPropertiesException;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingProperties;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\OptionalPropertiesBag;
use Tests\Fixtures\Values\TestBag;

covers(MissingProperties::class, MissingPropertiesException::class);

test('it does not error without missing properties', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

    $pipe = new MissingProperties();
    $input = $pipe($input);

    expect($input)->toBeInstanceOf(BagInput::class);
});

test('it does not error with missing optional properties', function () {
    $input = new BagInput(OptionalPropertiesBag::class, collect());
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

    $pipe = new MissingProperties();
    $input = $pipe($input);

    expect($input)->toBeInstanceOf(BagInput::class);

    $input = new BagInput(OptionalPropertiesBag::class, collect([
        'name' => 'Davey Shafik',
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

    $pipe = new MissingProperties();
    $input = $pipe($input);

    expect($input)->toBeInstanceOf(BagInput::class);
});

test('it errors with missing properties', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

    $pipe = new MissingProperties();
    $pipe($input);
})->throws(MissingPropertiesException::class, 'Missing required properties for Bag Tests\Fixtures\Values\TestBag: email');
