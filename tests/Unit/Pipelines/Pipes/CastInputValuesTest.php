<?php

declare(strict_types=1);
use Bag\Pipelines\Pipes\CastInputValues;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Carbon\CarbonImmutable;
use Illuminate\Support\Stringable;
use Tests\Fixtures\Values\CastInputOutputBag;
use Tests\Fixtures\Values\CastVariadicDatetimeBag;
use Tests\Fixtures\Values\VariadicBag;

covers(CastInputValues::class);

test('it casts input values', function () {
    $input = new BagInput(CastInputOutputBag::class, collect([
        'input' => 'test',
        'output' => 'testing',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new CastInputValues();
    $input = $pipe($input);

    expect($input->values->get('input'))->toBeInstanceOf(Stringable::class)
        ->and($input->values->get('input')->toString())->toBe('TEST');
});

test('it does not cast output values', function () {
    $input = new BagInput(CastInputOutputBag::class, collect([
        'input' => 'test',
        'output' => 'testing',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new CastInputValues();
    $input = $pipe($input);

    expect($input->values->get('output'))->toBeString()
        ->and($input->values->get('output'))->toBe('testing');
});

test('it casts variadics', function () {
    $input = new BagInput(CastVariadicDatetimeBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'test' => '2024-04-30',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new CastInputValues();
    $input = $pipe($input);

    expect($input->values->get('test'))->toBeInstanceOf(CarbonImmutable::class)
        ->and($input->values->get('test')->format('Y-m-d'))->toBe('2024-04-30');
});

test('it does not casts mixed variadic', function () {
    $input = new BagInput(VariadicBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'test' => true,
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new CastInputValues();
    $input = $pipe($input);

    expect($input->values->get('test'))->toBeTrue();
});
