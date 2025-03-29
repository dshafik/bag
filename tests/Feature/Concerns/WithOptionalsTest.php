<?php

declare(strict_types=1);

use Bag\Concerns\WithOptionals;
use Tests\Fixtures\Values\BagWithOptionals;

covers(WithOptionals::class);

test('has returns false with optional', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik']);

    expect($bag->has('age'))
        ->toBeFalse()
    ->and($bag->has('email'))
        ->toBeFalse();
});

test('has returns true with null', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    expect($bag->has('age'))
        ->toBeFalse()
    ->and($bag->has('email'))
        ->toBeTrue();
});

test('has returns true with value', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    expect($bag->has('age'))
        ->toBeTrue()
     ->and($bag->has('email'))
         ->toBeTrue();
});

test('hasAny returns false with optionals', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik']);

    expect($bag->hasAny('age', 'email'))
        ->toBeFalse();
});

test('hasAny returns true with null', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    expect($bag->hasAny('age', 'email'))
        ->toBeTrue();
});

test('hasAny returns true with values', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    expect($bag->hasAny('age', 'email'))
        ->toBeTrue();
});

test('hasAll returns false with optionals', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik']);

    expect($bag->hasAll('age', 'email'))
        ->toBeFalse();
});

test('hasAll returns false with null', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    expect($bag->hasAll('age', 'email'))
        ->toBeFalse();
});

test('hasAll returns true with values', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    expect($bag->hasALl('age', 'email'))
        ->toBeTrue();
});
