<?php

declare(strict_types=1);
use Bag\Exceptions\InvalidPropertyType;
use Bag\Internal\Util;
use Bag\Mappers\CamelCase;
use Bag\Mappers\Stringable;
use Illuminate\Pipeline\Pipeline;

covers(Util::class);

test('it gets property type', function () {
    $types = Util::getPropertyTypes(new \ReflectionParameter(fn (string $arg) => null, 'arg'));

    expect($types->first())->toBe('string');
});

test('it defaults to mixed type', function () {
    $types = Util::getPropertyTypes(new \ReflectionParameter(fn ($arg) => null, 'arg'));

    expect($types->first())->toBe('mixed');
});

test('it gets nullable types', function () {
    $types = Util::getPropertyTypes(new \ReflectionParameter(fn (string|null $arg) => null, 'arg'));

    expect($types->toArray())->toBe(['string', 'null']);

    $types = Util::getPropertyTypes(new \ReflectionParameter(fn (?string $arg) => null, 'arg'));

    expect($types->toArray())->toBe(['string', 'null']);
});

test('it gets all union types', function () {
    $types = Util::getPropertyTypes(new \ReflectionParameter(fn (int|string|float|bool|null $arg) => null, 'arg'));

    expect($types)
        ->toHaveCount(5)
    ->and($types->toArray())
        ->toBe(['string', 'int', 'float', 'bool', 'null']);
});

test('it errors on intersection type', function () {
    Util::getPropertyTypes(new \ReflectionParameter(fn (CamelCase&Stringable $anArgument) => null, 'anArgument'));
})->throws(InvalidPropertyType::class, 'Intersection types are not supported for parameter anArgument');

test('it creates a pipeline', function () {
    expect(Util::getPipeline())->toBeInstanceOf(Pipeline::class);
});
