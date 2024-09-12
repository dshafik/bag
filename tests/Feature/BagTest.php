<?php

declare(strict_types=1);

use Bag\Bag;
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
