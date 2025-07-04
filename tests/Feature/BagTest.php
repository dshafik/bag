<?php

declare(strict_types=1);

use Bag\Bag;
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Exceptions\MissingPropertiesException;
use Bag\Values\Optional;
use Tests\Fixtures\Enums\TestBackedEnum;
use Tests\Fixtures\Values\BagWithMappingAndOptional;
use Tests\Fixtures\Values\BagWithOptionals;
use Tests\Fixtures\Values\BagWithSingleArrayParameter;
use Tests\Fixtures\Values\BagWithUnionTypes;
use Tests\Fixtures\Values\NullablePropertiesBag;
use Tests\Fixtures\Values\NullableWithDefaultValueBag;
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
    $value = NullableWithDefaultValueBag::from([
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
    $value = NullableWithDefaultValueBag::from([]);

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
    $value = NullableWithDefaultValueBag::from();

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

test('it sets missing to Optional', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik']);

    expect($value->name)
        ->toBe('Davey Shafik')
    ->and($value->age)
        ->toBeInstanceOf(Optional::class)
    ->and($value->email)
        ->toBeInstanceOf(Optional::class);
});

test('it sets null Optionals to null', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    expect($value->name)
        ->toBe('Davey Shafik')
    ->and($value->age)
        ->toBeInstanceOf(Optional::class)
    ->and($value->email)
        ->toBeNull();
});

test('it hides Optionals from toArray', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik']);

    $array = $value->toArray();

    expect($array)
        ->toHaveKey('name')
        ->not->toHaveKey('age')
        ->not->toHaveKey('email')
    ->and($array['name'])
        ->toBe('Davey Shafik');
});

test('it does not hide null Optionals from toArray', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    $array = $value->toArray();

    expect($array)
        ->toHaveKey('name')
        ->not->toHaveKey('age')
        ->toHaveKey('email')
        ->and($array['name'])
        ->toBe('Davey Shafik')
        ->and($array['email'])
        ->toBeNull();
});

test('it hides Optionals from toJson', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik']);

    $json = json_decode($value->toJson(), true);

    expect($json)
        ->toHaveKey('name')
        ->not->toHaveKey('age')
        ->not->toHaveKey('email')
    ->and($json['name'])
        ->toBe('Davey Shafik');
});

test('it does not hide null Optionals from toJson', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    $json = json_decode($value->toJson(), true);

    expect($json)
        ->toHaveKey('name')
        ->not->toHaveKey('age')
        ->toHaveKey('email')
    ->and($json['name'])
        ->toBe('Davey Shafik')
    ->and($json['email'])
        ->toBeNull();
});

test('it hides Optionals from jsonSerialize', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik']);

    $json = $value->jsonSerialize();

    expect($json)
        ->toHaveKey('name')
        ->not->toHaveKey('age')
        ->not->toHaveKey('email')
    ->and($json['name'])
        ->toBe('Davey Shafik');
});

test('it does not hide null Optionals from jsonSerialize', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    $json = $value->jsonSerialize();

    expect($json)
        ->toHaveKey('name')
        ->not->toHaveKey('age')
        ->toHaveKey('email')
    ->and($json['name'])
        ->toBe('Davey Shafik')
    ->and($json['email'])
        ->toBeNull();
});

test('it can determine if a property is set with optionals', function () {
    $value = BagWithOptionals::from(['name' => 'Davey Shafik', 'email' => null]);

    expect($value->has('name'))
        ->toBeTrue()
    ->and($value->has('age'))
        ->toBeFalse()
    ->and($value->has('email'))
        ->toBeTrue();
});

test('it fails when non-optionals are missing', function () {
    $bag = BagWithOptionals::from([]);
})->throws(MissingPropertiesException::class, 'Missing required properties for Bag Tests\Fixtures\Values\BagWithOptionals: name');

test('it handles mapping and optionals', function () {
    $bag = BagWithMappingAndOptional::from([
        'name' => 'Davey Shafik',
        'current_age' => 40,
    ]);

    expect($bag->toArray())->toBe([
        'name' => 'Davey Shafik',
        'currentAge' => 40,
    ]);

    $bag = BagWithMappingAndOptional::from([
        'name' => 'Davey Shafik',
    ]);

    expect($bag->toArray())->toBe([
        'name' => 'Davey Shafik',
    ]);
});
