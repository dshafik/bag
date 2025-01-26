<?php

declare(strict_types=1);
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessArguments;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\StripExtraParameters;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Support\Collection;
use Tests\Fixtures\Values\StripExtraParametersBag;
use Tests\Fixtures\Values\VariadicBag;

covers(ExtraParameters::class, StripExtraParameters::class);

test('it does not error on non variadic with extra parameters ignored', function () {
    $input = new BagInput(StripExtraParametersBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
        'test' => true,
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);
    $input = (new ExtraParameters())($input, fn (BagInput $input) => $input);

    $pipe = new StripExtraParameters();
    $input = $pipe($input);

    expect($input)
        ->toBeInstanceOf(BagInput::class)
        ->and($input->values)
        ->toBeInstanceOf(Collection::class)
        ->and($input->values->toArray())
        ->toBe([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);
});


test('it does not error on non variadic with extra positional parameters ignore', function () {
    $input = new BagInput(StripExtraParametersBag::class, collect([
        'Davey Shafik',
        40,
        'davey@php.net',
        true,
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new ProcessArguments())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);
    $input = (new ExtraParameters())($input, fn (BagInput $input) => $input);

    $pipe = new StripExtraParameters();
    $input = $pipe($input);

    expect($input)
        ->toBeInstanceOf(BagInput::class)
        ->and($input->values)
        ->toBeInstanceOf(Collection::class)
        ->and($input->values->toArray())
        ->toBe([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);
});

test('it does not strip extra parameters with variadic', function () {
    $input = new BagInput(VariadicBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
        'test' => true,
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);
    $input = (new IsVariadic())($input, fn (BagInput $input) => $input);
    $input = (new ExtraParameters())($input, fn (BagInput $input) => $input);

    $pipe = new StripExtraParameters();
    $input = $pipe($input);

    expect($input)
        ->toBeInstanceOf(BagInput::class)
        ->and($input->values)
        ->toBeInstanceOf(Collection::class)
        ->and($input->values->toArray())
        ->toBe([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
            'test' => true,
        ]);
});
