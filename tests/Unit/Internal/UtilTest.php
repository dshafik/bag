<?php

declare(strict_types=1);
use Bag\Exceptions\InvalidPropertyType;
use Bag\Internal\Util;
use Bag\Mappers\CamelCase;
use Bag\Mappers\Stringable;
use Illuminate\Pipeline\Pipeline;

test('it gets property type', function () {
    $type = Util::getPropertyType(new \ReflectionParameter(fn (string $arg) => null, 'arg'));

    expect($type->getName())->toBe('string');
});

test('it defaults to mixed type', function () {
    $type = Util::getPropertyType(new \ReflectionParameter(fn ($arg) => null, 'arg'));

    expect($type->getName())->toBe('mixed');
});

test('it uses first union type', function () {
    $type = Util::getPropertyType(new \ReflectionParameter(fn (int|string|float|bool $arg) => null, 'arg'));

    expect($type->getName())->toBe('string');
});

test('it errors on intersection type', function () {
    $this->expectException(InvalidPropertyType::class);
    $this->expectExceptionMessage('Intersection types are not supported for parameter anArgument');
    Util::getPropertyType(new \ReflectionParameter(fn (CamelCase&Stringable $anArgument) => null, 'anArgument'));
});

test('it creates a pipeline', function () {
    expect(Util::getPipeline())->toBeInstanceOf(Pipeline::class);
});
