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

test('it gets all union types', function () {
    $types = Util::getPropertyTypes(new \ReflectionParameter(fn (int|string|float|bool $arg) => null, 'arg'));

    expect($types)
        ->toHaveCount(4)
    ->and($types->toArray())
        ->toBe(['string', 'int', 'float', 'bool']);
});

test('it errors on intersection type', function () {
    $this->expectException(InvalidPropertyType::class);
    $this->expectExceptionMessage('Intersection types are not supported for parameter anArgument');
    Util::getPropertyTypes(new \ReflectionParameter(fn (CamelCase&Stringable $anArgument) => null, 'anArgument'));
});

test('it creates a pipeline', function () {
    expect(Util::getPipeline())->toBeInstanceOf(Pipeline::class);
});
