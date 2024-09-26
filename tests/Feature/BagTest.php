<?php

declare(strict_types=1);

use Bag\Bag;
use Tests\Fixtures\Values\OptionalPropertiesBag;
use Tests\Fixtures\Values\TestBag;

covers(Bag::class);

test('it creates value from array', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);

    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');
});

test('it creates new instance using with named args', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 39,
        'email' => 'davey@php.net',
    ]);

    $value2 = $value->with(age: 40, email: 'test@example.org');

    expect($value)->not->toBe($value2)
        ->and($value2->name)->toBe('Davey Shafik')
        ->and($value2->age)->toBe(40)
        ->and($value2->email)->toBe('test@example.org');
});

test('it creates new instance using with array', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 39,
        'email' => 'davey@php.net',
    ]);

    $value2 = $value->with(['age' => 40, 'email' => 'test@example.org']);

    expect($value)->not->toBe($value2)
        ->and($value2->name)->toBe('Davey Shafik')
        ->and($value2->age)->toBe(40)
        ->and($value2->email)->toBe('test@example.org');
});

test('it errors on non-nullables', function () {
    $value = TestBag::from([
        'name' => null,
        'age' => null,
        'email' => null
    ]);
})->throws(\TypeError::class, 'Tests\Fixtures\Values\TestBag::__construct(): Argument #1 ($name) must be of type string, null given');

test('it allows nullables with explicit nulls', function () {
    $value = OptionalPropertiesBag::from([
        'name' => null,
        'age' => null,
        'email' => null,
        'bag' => null,
    ]);

    expect($value->name)->toBeNull()
        ->and($value->age)->toBeNull()
        ->and($value->email)->toBeNull()
        ->and($value->bag)->toBeNull();
});

test('it allows nullables with implicit nulls', function () {
    $value = OptionalPropertiesBag::from([]);

    expect($value->name)->toBeNull()
        ->and($value->age)->toBeNull()
        ->and($value->email)->toBeNull()
        ->and($value->bag)->toBeNull();
});
