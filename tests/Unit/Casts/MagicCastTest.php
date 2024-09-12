<?php

declare(strict_types=1);
use Bag\Casts\MagicCast;
use Bag\Collection;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon as LaravelCarbon;
use Illuminate\Support\Collection as LaravelCollection;
use Tests\Fixtures\Enums\TestBackedEnum;
use Tests\Fixtures\Enums\TestUnitEnum;
use Tests\Fixtures\Values\TestBag;

covers(MagicCast::class);

test('it casts int', function () {
    $cast = new MagicCast();

    $int = $cast->set('int', 'test', collect(['test' => 1]));
    expect($int)
        ->toBeInt()
        ->toBe(1);

    $int = $cast->set('int', 'test', collect(['test' => 1.7]));
    expect($int)
        ->toBeInt()
        ->toBe(1);
});

test('it casts float', function () {
    $cast = new MagicCast();

    $float = $cast->set('float', 'test', collect(['test' => 1]));
    expect($float)
        ->toBeFloat()
        ->toBe(1.0);

    $float = $cast->set('float', 'test', collect(['test' => 1.7]));
    expect($float)
        ->toBeFloat()
        ->toBe(1.7);
});

test('it casts boolean', function () {
    $cast = new MagicCast();

    $boolean = $cast->set('bool', 'test', collect(['test' => 0]));
    expect($boolean)->toBeFalse();

    $boolean = $cast->set('bool', 'test', collect(['test' => 1]));
    expect($boolean)->toBeTrue();
});

test('it casts string', function () {
    $cast = new MagicCast();

    $string = $cast->set('string', 'test', collect(['test' => 'testing']));
    expect($string)
        ->toBeString()
        ->toBe('testing');

    $string = $cast->set('string', 'test', collect(['test' => 1234]));
    expect($string)
        ->toBeString()
        ->toBe('1234');
});

test('it casts date times', function () {
    $cast = new MagicCast();

    $date = $cast->set(\DateTime::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
    expect($date)->toBeInstanceOf(\DateTime::class)
        ->and($date->format('Y-m-d H:i:s'))->toBe('2024-04-30 11:43:41');

    $date = $cast->set(\DateTimeImmutable::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
    expect($date)->toBeInstanceOf(\DateTimeImmutable::class)
        ->and($date->format('Y-m-d H:i:s'))->toBe('2024-04-30 11:43:41');

    $date = $cast->set(Carbon::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
    expect($date)->toBeInstanceOf(Carbon::class)
        ->and($date->format('Y-m-d H:i:s'))->toBe('2024-04-30 11:43:41');

    $date = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
    expect($date)->toBeInstanceOf(CarbonImmutable::class)
        ->and($date->format('Y-m-d H:i:s'))->toBe('2024-04-30 11:43:41');

    $date = $cast->set(LaravelCarbon::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
    expect($date)->toBeInstanceOf(LaravelCarbon::class)
        ->and($date->format('Y-m-d H:i:s'))->toBe('2024-04-30 11:43:41');

    $class = new class () extends CarbonImmutable { };
    $date = $cast->set($class::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
    expect($date)->toBeInstanceOf($class::class)
        ->and($date->format('Y-m-d H:i:s'))->toBe('2024-04-30 11:43:41');
});

test('it casts bags', function () {
    $cast = new MagicCast();

    $bag = $cast->set(TestBag::class, 'test', collect([
        'test' => [
            'name' => 'Davey Shafik',
            'age' => '40',
            'email' => 'davey@php.net'
        ]
    ]));

    expect($bag)->toBeInstanceOf(TestBag::class)
        ->and($bag->name)->toBe('Davey Shafik')
        ->and($bag->age)->toBe(40)
        ->and($bag->email)->toBe('davey@php.net');
});

test('it casts collections', function () {
    $cast = new MagicCast();

    $collection = $cast->set(LaravelCollection::class, 'test', collect([
        'test' => [
            'name' => 'Davey Shafik',
            'age' => '40',
            'email' => 'davey@php.net'
        ]
    ]));
    expect($collection)->toBeInstanceOf(LaravelCollection::class)
        ->and($collection['name'])->toBe('Davey Shafik')
        ->and($collection['age'])->toBe('40')
        ->and($collection['email'])->toBe('davey@php.net');

    $collection = $cast->set(Collection::class, 'test', collect([
        'test' => [
            'name' => 'Davey Shafik',
            'age' => '40',
            'email' => 'davey@php.net'
        ]
    ]));
    expect($collection)->toBeInstanceOf(Collection::class)
        ->and($collection['name'])->toBe('Davey Shafik')
        ->and($collection['age'])->toBe('40')
        ->and($collection['email'])->toBe('davey@php.net');
});

test('it casts unit enum', function () {
    $cast = new MagicCast();

    $enum = $cast->set(TestUnitEnum::class, 'test', collect(['test' => 'TEST_VALUE']));

    expect($enum)->toBe(TestUnitEnum::TEST_VALUE);
});

test('it casts backed enum', function () {
    $cast = new MagicCast();

    $enum = $cast->set(TestBackedEnum::class, 'test', collect(['test' => 'test']));

    expect($enum)->toBe(TestBackedEnum::TEST_VALUE);
});
