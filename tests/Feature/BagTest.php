<?php

declare(strict_types=1);

use Bag\Bag;
use Bag\Collection;
use Bag\Exceptions\AdditionalPropertiesException;
use Illuminate\Support\Collection as LaravelCollection;
use Tests\Fixtures\Collections\ExtendsBagWithCollectionCollection;
use Tests\Fixtures\Values\BagWithLotsOfTypes;
use Tests\Fixtures\Values\BagWithSingleArrayParameter;
use Tests\Fixtures\Values\ExtendsTestBag;
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

test('it errors on non-nullables', function () {
    $value = TestBag::from([
        'name' => null,
        'age' => null,
        'email' => null,
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
    'Additional properties found: extra, foo'
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

test('it creates an empty bag', function () {
    $value = TestBag::empty();

    expect($value)
        ->toBeInstanceOf(TestBag::class)
        ->and($value->name)->toBe('')
        ->and($value->age)->toBe(0)
        ->and($value->email)->toBe('');
});

test('it creates complex empty bag', function () {
    $value = BagWithLotsOfTypes::empty();

    expect($value)
        ->toBeInstanceOf(BagWithLotsOfTypes::class)
        ->and($value->name)->toBe('')
        ->and($value->age)->toBe(0)
        ->and($value->is_active)->toBeFalse()
        ->and($value->price)->toBe(0.0)
        ->and($value->items)->toBe([])
        ->and($value->object)->toBeInstanceOf(\stdClass::class)
        ->and($value->mixed)->toBeNull()
        ->and($value->bag)->toBeInstanceOf(TestBag::class)
        ->and($value->bag->name)->toBe('')
        ->and($value->bag->age)->toBe(0)
        ->and($value->bag->email)->toBe('')
        ->and($value->collection)->toBeInstanceOf(LaravelCollection::class)
        ->and($value->collection->isEmpty())->toBeTrue()
        ->and($value->nullable_string)->toBeNull()
        ->and($value->nullable_int)->toBeNull()
        ->and($value->nullable_bool)->toBeNull()
        ->and($value->nullable_float)->toBeNull()
        ->and($value->nullable_array)->toBeNull()
        ->and($value->nullable_object)->toBeNull()
        ->and($value->nullable_bag)->toBeNull()
        ->and($value->nullable_collection)->toBeNull()
        ->and($value->optional_string)->toBe('optional')
        ->and($value->optional_int)->toBe(100)
        ->and($value->optional_bool)->toBeTrue()
        ->and($value->optional_float)->toBe(100.2)
        ->and($value->optional_array)->toBe(['optional'])
        ->and($value->optional_object)->toBeInstanceOf(\WeakMap::class)
        ->and($value->optional_mixed)->toBeInstanceOf(\WeakMap::class)
        ->and($value->optional_bag)->toBeInstanceOf(ExtendsTestBag::class)
        ->and($value->optional_bag?->name)->toBe('Davey Shafik')
        ->and($value->optional_bag?->age)->toBe(40)
        ->and($value->optional_bag?->email)->toBe('davey@php.net')
        ->and($value->optional_collection)->toBeInstanceOf(Collection::class)
        ->and($value->optional_custom_collection)->toBeInstanceOf(ExtendsBagWithCollectionCollection::class);
});


test('it creates partial bags', function () {
    $value = TestBag::partial(name: 'Davey Shafik');

    expect($value)
        ->toBeInstanceOf(TestBag::class)
        ->and($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(0)
        ->and($value->email)->toBe('');

    $value = TestBag::partial(age: 40);

    expect($value)
        ->toBeInstanceOf(TestBag::class)
        ->and($value->name)->toBe('')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('');

    $value = TestBag::partial(email: 'davey@php.net');

    expect($value)
        ->toBeInstanceOf(TestBag::class)
        ->and($value->name)->toBe('')
        ->and($value->age)->toBe(0)
        ->and($value->email)->toBe('davey@php.net');
})->skip(!method_exists(Bag::class, 'partial'));
