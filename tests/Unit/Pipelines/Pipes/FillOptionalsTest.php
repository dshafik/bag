<?php

declare(strict_types=1);

use Bag\Pipelines\Pipes\FillOptionals;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Bag\Values\Optional;
use Tests\Fixtures\Values\BagWithOptionals;

covers(FillOptionals::class);

test('it sets missing optionals to Optional', function () {
    $input = new BagInput(BagWithOptionals::class, collect(['name' => 'Davey Shafik']));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new FillOptionals();
    $input = $pipe($input);

    expect($input->values->get('name'))
        ->toBe('Davey Shafik')
        ->and($input->values->get('age'))
        ->toBeInstanceOf(Optional::class)
        ->and($input->values->get('email'))
        ->toBeInstanceOf(Optional::class);
});

test('it sets nullable optionals explicitly to null', function () {
    $input = new BagInput(BagWithOptionals::class, collect(['name' => 'Davey Shafik', 'email' => null]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new FillOptionals();
    $input = $pipe($input);

    expect($input->values->get('name'))
        ->toBe('Davey Shafik')
    ->and($input->values->get('age'))
        ->toBeInstanceOf(Optional::class)
    ->and($input->values->get('email'))
        ->toBeNull();
});

test('it sets optionals to specified values', function () {
    $input = new BagInput(BagWithOptionals::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new FillOptionals();
    $input = $pipe($input);

    expect($input->values->get('name'))
        ->toBe('Davey Shafik')
    ->and($input->values->get('age'))
        ->toBe(40)
    ->and($input->values->get('email'))
        ->toBe('davey@php.net');
});


test('it sets non-nullable optionals to Optional when null', function () {
    $input = new BagInput(BagWithOptionals::class, collect([
        'name' => 'Davey Shafik',
        'age' => null,
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);

    $pipe = new FillOptionals();
    $input = $pipe($input);

    expect($input->values->get('name'))
        ->toBe('Davey Shafik')
    ->and($input->values->get('age'))
        ->toBeInstanceOf(Optional::class)
    ->and($input->values->get('email'))
        ->toBeInstanceOf(Optional::class);
});
