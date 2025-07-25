<?php

declare(strict_types=1);
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessArguments;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\VariadicBag;

covers(ExtraParameters::class, AdditionalPropertiesException::class);

test('it does not error without extra parameters', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new ExtraParameters();
    $input = $pipe($input);

    expect($input)->toBeInstanceOf(BagInput::class);
});

test('it does not error with variadic bag with extra parameters', function () {
    $input = new BagInput(VariadicBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new ExtraParameters();
    $input = $pipe($input);

    expect($input)->toBeInstanceOf(BagInput::class);
});

test('it errors on non variadic with extra parameters', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
        'test' => true,
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new ExtraParameters();
    $pipe($input);
})->throws(AdditionalPropertiesException::class, 'Additional properties found for bag (Tests\Fixtures\Values\TestBag): test');


test('it errors on non variadic with extra positional parameters', function () {
    $input = new BagInput(TestBag::class, collect([
        'Davey Shafik',
        40,
        'davey@php.net',
        true,
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new ProcessArguments())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new ExtraParameters();
    $pipe($input);
})->throws(\ArgumentCountError::class, 'Tests\Fixtures\Values\TestBag::from(): Too many arguments passed, expected 3, got 4');
