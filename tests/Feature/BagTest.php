<?php

declare(strict_types=1);

use Bag\Bag;
use Bag\Exceptions\AdditionalPropertiesException;
use Tests\Fixtures\Enums\TestBackedEnum;
use Tests\Fixtures\Values\BagWithSingleArrayParameter;
use Tests\Fixtures\Values\BagWithUnionTypes;
use Tests\Fixtures\Values\NullablePropertiesBag;
use Tests\Fixtures\Values\OptionalPropertiesBag;
use Tests\Fixtures\Values\OptionalPropertiesWithDefaultsBag;
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


test('it creates value from collection', function () {
    $value = TestBag::from(collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]));

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

test('it creates new instance using append with named args', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 39,
        'email' => 'davey@php.net',
    ]);

    $value2 = $value->append(age: 40, email: 'test@example.org');

    expect($value)->not->toBe($value2)
        ->and($value2->name)->toBe('Davey Shafik')
        ->and($value2->age)->toBe(40)
        ->and($value2->email)->toBe('test@example.org');
});

test('it creates new instance using append with array', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 39,
        'email' => 'davey@php.net',
    ]);

    $value2 = $value->append(['age' => 40, 'email' => 'test@example.org']);

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

test('it uses default values', function () {
    $value = OptionalPropertiesWithDefaultsBag::from();

    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net')
        ->and($value->bag)->toBeNull();
});

test('it sets nullable without input', function () {
    $value = NullablePropertiesBag::from();

    expect($value->name)->toBeNull()
        ->and($value->age)->toBeNull()
        ->and($value->email)->toBeNull()
        ->and($value->bag)->toBeNull();
});

test('it allows nullables with no values', function () {
    $value = OptionalPropertiesBag::from();

    expect($value->name)->toBeNull()
        ->and($value->age)->toBeNull()
        ->and($value->email)->toBeNull()
        ->and($value->bag)->toBeNull();
});

test('it accepts named params', function () {
    $value = TestBag::from(name: 'Davey Shafik', age: 40, email: 'davey@php.net');

    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');
});

test('it handles single array params as array', function () {
    $value = BagWithSingleArrayParameter::from(['items' => [1, 2, 3]]);

    expect($value->items)->toBe([1, 2, 3]);
});

test('it handles single array params as named args', function () {
    $value = BagWithSingleArrayParameter::from(items: [1, 2, 3]);

    expect($value->items)->toBe([1, 2, 3]);
});

test('it handles single array params as positional args', function () {
    $value = BagWithSingleArrayParameter::from([1, 2, 3]);

    expect($value->items)->toBe([1, 2, 3]);
});

test('it rejects extra named params', function () {
    $value = TestBag::from(name: 'Davey Shafik', age: 40, email: 'davey@php.net', extra: 'extra', foo: 'bar');
})->throws(
    AdditionalPropertiesException::class,
    'Additional properties found for bag (Tests\Fixtures\Values\TestBag): extra, foo'
);

test('it accepts ordered params', function () {
    $value = TestBag::from('Davey Shafik', 40, 'davey@php.net');

    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');
});

test('it rejects extra ordered params', function () {
    $value = TestBag::from('Davey Shafik', 40, 'davey@php.net', 'extra');
})->throws(
    \ArgumentCountError::class,
    'Tests\Fixtures\Values\TestBag::from(): Too many arguments passed, expected 3, got 4'
);

test('union types', function () {
    $value = BagWithUnionTypes::from(name: 'Davey Shafik', age: 40, email: 'davey@php.net');
    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');

    $value = BagWithUnionTypes::from(name: TestBackedEnum::TEST_VALUE, age: '40', email: false);
    expect($value->name)->toBe(TestBackedEnum::TEST_VALUE)
        ->and($value->age)->toBe('40')
        ->and($value->email)->toBe(false);
});

test('it can be var_exported', function () {
    $value = TestBag::from('Davey Shafik', 40, 'davey@php.net');

    $exported = var_export($value, true);

    expect($exported)->toBe('\Tests\Fixtures\Values\TestBag::__set_state(array(' . PHP_EOL . '   \'name\' => \'Davey Shafik\',' . PHP_EOL . '   \'age\' => 40,' . PHP_EOL . '   \'email\' => \'davey@php.net\',' . PHP_EOL . '))');

    $imported = eval('return ' . $exported . ';');

    expect($imported)->toBeInstanceOf(TestBag::class)
    ->and($imported->toArray())->toBe($value->toArray());
});

test('it can be serialized and unserialized', function () {
    $value = TestBag::from('Davey Shafik', 40, 'davey@php.net');

    $serialized = serialize($value);
    $unserialized = unserialize($serialized);

    /** @var TestBag $unserialized */
    expect($unserialized)->toBeInstanceOf(TestBag::class)
    ->and($unserialized->toArray())->toBe($value->toArray());
});
