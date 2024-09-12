<?php

declare(strict_types=1);

use Bag\Concerns\WithArrayable;
use Tests\Fixtures\Values\TestBag;

covers(WithArrayable::class);

test('it is arrayable', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ])->toArray();

    expect($value['name'])->toBe('Davey Shafik')
        ->and($value['age'])->toBe(40)
        ->and($value['email'])->toBe('davey@php.net');
});
