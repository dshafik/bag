<?php

declare(strict_types=1);

use Bag\Pipelines\Pipes\ProcessArguments;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Support\Collection;
use Tests\Fixtures\Values\OptionalNestedBags;
use Tests\Fixtures\Values\TestBag;

covers(ProcessArguments::class);

test('it handles array arguments', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]));

    $pipe = new ProcessArguments();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->count())->toBe(3)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it handles named arguments', function () {
    $input = new BagInput(TestBag::class, collect([[
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]]));

    $pipe = new ProcessArguments();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->count())->toBe(3)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it handles positional arguments', function () {
    $input = new BagInput(TestBag::class, collect([
        'Davey Shafik',
        40,
        'davey@php.net',
    ]));

    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $pipe = new ProcessArguments();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->count())->toBe(3)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it handles single array arguments', function () {
    $input = new BagInput(OptionalNestedBags::class, collect([
        'test' => [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]
    ]));

    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $pipe = new ProcessArguments();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->count())->toBe(1)
        ->and($input->input->get('test'))->toBe([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);
});
