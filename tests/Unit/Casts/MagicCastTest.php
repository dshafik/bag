<?php

declare(strict_types=1);
use Bag\Casts\MagicCast;
use Bag\Collection;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon as LaravelCarbon;
use Illuminate\Support\Collection as LaravelCollection;
use Mockery\MockInterface;
use Tests\Fixtures\Enums\TestBackedEnum;
use Tests\Fixtures\Enums\TestUnitEnum;
use Tests\Fixtures\Values\TestBag;

covers(MagicCast::class);

dataset('int', [
    ['int', 'test', collect(['test' => 1]), 1],
    ['int', 'test', collect(['test' => 1.7]), 1],
]);

dataset('float', [
    ['float', 'test', collect(['test' => 1]), 1.0],
    ['float', 'test', collect(['test' => 1.7]), 1.7],
]);

dataset('bool', [
    ['bool', 'test', collect(['test' => 0]), false],
    ['bool', 'test', collect(['test' => 1]), true],
]);

dataset('string', [
    ['string', 'test', collect(['test' => 'testing']), 'testing'],
    ['string', 'test', collect(['test' => 1234]), '1234'],
]);

dataset('date_times', [
    [\DateTime::class, 'test', collect(['test' => '2024-04-30 11:43:41']), \DateTime::class, '2024-04-30 11:43:41'],
    [\DateTimeImmutable::class, 'test', collect(['test' => '2024-04-30 11:43:41']), \DateTimeImmutable::class, '2024-04-30 11:43:41'],
    [Carbon::class, 'test', collect(['test' => '2024-04-30 11:43:41']), Carbon::class, '2024-04-30 11:43:41'],
    [CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 11:43:41']), CarbonImmutable::class, '2024-04-30 11:43:41'],
    [LaravelCarbon::class, 'test', collect(['test' => '2024-04-30 11:43:41']), LaravelCarbon::class, '2024-04-30 11:43:41'],
]);

dataset('bags', [
    [TestBag::class, 'test', collect(['test' => ['name' => 'Davey Shafik', 'age' => '40', 'email' => 'davey@php.net']]), TestBag::class, 'Davey Shafik', 40, 'davey@php.net'],
]);

dataset('collections', [
    [LaravelCollection::class, 'test', collect(['test' => ['name' => 'Davey Shafik', 'age' => '40', 'email' => 'davey@php.net']]), LaravelCollection::class, 'Davey Shafik', '40', 'davey@php.net'],
    [Collection::class, 'test', collect(['test' => ['name' => 'Davey Shafik', 'age' => '40', 'email' => 'davey@php.net']]), Collection::class, 'Davey Shafik', '40', 'davey@php.net'],
]);

dataset('unit_enum', [
    [TestUnitEnum::class, 'test', collect(['test' => 'TEST_VALUE']), TestUnitEnum::TEST_VALUE],
]);

dataset('backed_enum', [
    [TestBackedEnum::class, 'test', collect(['test' => 'test']), TestBackedEnum::TEST_VALUE],
]);

dataset('nullable', [
    ['int'],
    ['float'],
    ['bool'],
    ['string'],
    [\DateTime::class],
    [\DateTimeImmutable::class],
    [Carbon::class],
    [CarbonImmutable::class],
    [LaravelCarbon::class],
    [TestBag::class],
    [LaravelCollection::class],
    [Collection::class],
    [TestUnitEnum::class],
    [TestBackedEnum::class],
]);

test('it casts int', function ($propertyType, $propertyName, $properties, $expected) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBe($expected);
})->with('int');

test('it casts float', function ($propertyType, $propertyName, $properties, $expected) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBe($expected);
})->with('float');

test('it casts boolean', function ($propertyType, $propertyName, $properties, $expected) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBe($expected);
})->with('bool');

test('it casts string', function ($propertyType, $propertyName, $properties, $expected) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBe($expected);
})->with('string');

test('it casts date times', function ($propertyType, $propertyName, $properties, $expectedClass, $expectedFormat) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBeInstanceOf($expectedClass)
        ->and($result->format('Y-m-d H:i:s'))->toBe($expectedFormat);
})->with('date_times');

test('it casts bags', function ($propertyType, $propertyName, $properties, $expectedClass, $expectedName, $expectedAge, $expectedEmail) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBeInstanceOf($expectedClass)
        ->and($result->name)->toBe($expectedName)
        ->and($result->age)->toBe($expectedAge)
        ->and($result->email)->toBe($expectedEmail);
})->with('bags');

test('it casts collections', function ($propertyType, $propertyName, $properties, $expectedClass, $expectedName, $expectedAge, $expectedEmail) {
    $cast = new MagicCast();

    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBeInstanceOf($expectedClass)
        ->and($result['name'])->toBe($expectedName)
        ->and($result['age'])->toBe($expectedAge)
        ->and($result['email'])->toBe($expectedEmail);
})->with('collections');

test('it casts unit enum', function ($propertyType, $propertyName, $properties, $expected) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBe($expected);
})->with('unit_enum');

test('it casts backed enum', function ($propertyType, $propertyName, $properties, $expected) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), $propertyName, $properties);
    expect($result)->toBe($expected);
})->with('backed_enum');

test('it casts to null if value is null', function ($propertyType) {
    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($propertyType), 'test', collect(['test' => null]));

    expect($result)->toBeNull();
})->with('nullable');

test('it casts model IDs to models', function () {
    $model = new class () extends Model {
        public $id = 1;
    };

    $mock = mock($model::class, function (MockInterface $mock) use ($model) {
        $mock
            ->shouldReceive('findOrFail')
            ->with(123)
            ->andReturn($model);
    });

    $cast = new MagicCast();
    $result = $cast->set(Collection::wrap($mock::class), 'test', collect(['test' => 123]));

    expect($result)->toBeInstanceOf($model::class)
        ->and($result->id)->toBe(1);
});
