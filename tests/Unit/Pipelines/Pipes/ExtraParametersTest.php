<?php

declare(strict_types=1);
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\VariadicBag;

test('it does not error without extra parameters', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

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
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

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
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

    $this->expectException(AdditionalPropertiesException::class);
    $this->expectExceptionMessage('Additional properties found: test');
    $pipe = new ExtraParameters();
    $pipe($input);
});
