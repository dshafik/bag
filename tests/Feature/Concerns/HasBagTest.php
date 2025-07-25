<?php

declare(strict_types=1);

use Bag\Attributes\Bag;
use Bag\Exceptions\BagAttributeNotFoundException;
use Bag\Exceptions\BagNotFoundException;
use Bag\Traits\HasBag;
use Tests\Fixtures\ObjectToBagAll;
use Tests\Fixtures\ObjectToBagInvalidBag;
use Tests\Fixtures\ObjectToBagNoAttribute;
use Tests\Fixtures\ObjectToBagProtected;
use Tests\Fixtures\ObjectToBagPublic;
use Tests\Fixtures\Values\NullableWithDefaultValueBag;
use Tests\Fixtures\Values\ObjectToBagPrivate;

covers(
    HasBag::class,
    BagNotFoundException::class,
    BagAttributeNotFoundException::class,
    Bag::class
);

test('it creates bag with public properties', function () {
    $object = new ObjectToBagPublic('Davey Shafik', 40, 'davey@php.net');

    /** @var NullableWithDefaultValueBag $bag */
    $bag = $object->toBag();
    expect($bag->name)->toBe('Davey Shafik')
        ->and($bag->age)->toBeNull()
        ->and($bag->email)->toBeNull();
});

test('it creates bag with public and protected properties', function () {
    $object = new ObjectToBagProtected('Davey Shafik', 40, 'davey@php.net');

    /** @var NullableWithDefaultValueBag $bag */
    $bag = $object->toBag();
    expect($bag->name)->toBe('Davey Shafik')
        ->and($bag->age)->toBe(40)
        ->and($bag->email)->toBeNull();
});

test('it creates bag with public protected and private properties', function () {
    $object = new ObjectToBagAll('Davey Shafik', 40, 'davey@php.net');

    /** @var NullableWithDefaultValueBag $bag */
    $bag = $object->toBag();
    expect($bag->name)->toBe('Davey Shafik')
        ->and($bag->age)->toBe(40)
        ->and($bag->email)->toBe('davey@php.net');
});

test('it creates bag with private properties', function () {
    $object = new ObjectToBagPrivate('Davey Shafik', 40, 'davey@php.net');

    /** @var NullableWithDefaultValueBag $bag */
    $bag = $object->toBag();
    expect($bag->name)->toBeNull()
        ->and($bag->age)->toBeNull()
        ->and($bag->email)->toBe('davey@php.net');
});

test('it errors when no attribute found', function () {
    (new ObjectToBagNoAttribute())->toBag();
})->throws(BagAttributeNotFoundException::class, 'Bag attribute not found on class ' . ObjectToBagNoAttribute::class);

test('it errors when bag does not exist', function () {
    (new ObjectToBagInvalidBag())->toBag();
})->throws(BagNotFoundException::class, 'The Bag class "InvalidBagName" does not exist');
