<?php

declare(strict_types=1);

use Bag\Attributes\Collection as AttributesCollection;
use Bag\Collection;
use Bag\Internal\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\BagWithCollection;

covers(Reflection::class);

test('it gets class', function () {
    $class = Reflection::getClass(static::class);
    expect($class->getName())->toBe(static::class);
});

test('it gets class with reflection class', function () {
    $class = Reflection::getClass(new \ReflectionClass(static::class));
    expect($class->getName())->toBe(static::class);
});

test('it gets constructor', function () {
    $constructor = Reflection::getConstructor(static::class);
    expect($constructor)->toBeInstanceOf(\ReflectionMethod::class)
        ->and($constructor->getName())->toBe('__construct');
});

test('it gets constructor on existing reflection class', function () {
    $class = new \ReflectionClass(static::class);
    $constructor = Reflection::getConstructor($class);
    expect($constructor)->toBeInstanceOf(\ReflectionMethod::class)
        ->and($constructor->getName())->toBe('__construct');
});

test('it returns null when no constructor', function () {
    expect(Reflection::getConstructor(Reflection::class))->toBeNull();
});

test('it gets properties', function () {
    $properties = Reflection::getProperties(static::class);
    expect($properties)->toBeInstanceOf(Collection::class);
});

test('it returns empty when getting properties on null', function () {
    expect(Reflection::getProperties(null))->toBeEmpty();
});

test('it gets parameters', function () {
    $method = new \ReflectionMethod(static::class, 'setUp');
    $parameters = Reflection::getParameters($method);
    expect($parameters)->toBeInstanceOf(Collection::class);
});

test('it returns empty when getting parameters on null', function () {
    expect(Reflection::getParameters(null))->toBeEmpty();
});

test('it gets attributes', function () {
    $class = new \ReflectionClass(static::class);
    $attributes = Reflection::getAttributes($class, CoversClass::class);
    expect($attributes)->toBeInstanceOf(Collection::class);
});

test('it returns empty when getting attributes on null', function () {
    expect(Reflection::getAttributes(null, CoversClass::class))->toBeEmpty();
});

test('it gets attribute', function () {
    $class = new \ReflectionClass(BagWithCollection::class);
    $attribute = Reflection::getAttribute($class, AttributesCollection::class);
    expect($attribute)->toBeInstanceOf(\ReflectionAttribute::class);
});

test('it returns null when getting non existent attribute', function () {
    $class = new \ReflectionClass(static::class);
    expect(Reflection::getAttribute($class, 'NonExistentAttribute'))->toBeNull();
});

test('it returns null when getting attribute on null', function () {
    expect(Reflection::getAttribute(null, CoversClass::class))->toBeNull();
});

test('it gets attribute instance', function () {
    $class = new \ReflectionClass(BagWithCollection::class);
    $instance = Reflection::getAttributeInstance($class, AttributesCollection::class);
    expect($instance)->toBeObject();
});

test('it returns null when getting attribute instance with non existent attribute', function () {
    $class = new \ReflectionClass(static::class);
    expect(Reflection::getAttributeInstance($class, 'NonExistentAttribute'))->toBeNull();
});

test('it returns null when getting attribute instance on null', function () {
    expect(Reflection::getAttributeInstance(null, CoversClass::class))->toBeNull();
});

test('it gets attribute arguments', function () {
    $class = Reflection::getClass(BagWithCollection::class);
    $attribute = Reflection::getAttribute($class, AttributesCollection::class);
    $arguments = Reflection::getAttributeArguments($attribute);
    expect($arguments)->toBeInstanceOf(Collection::class);
});
