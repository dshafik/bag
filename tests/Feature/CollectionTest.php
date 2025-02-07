<?php

declare(strict_types=1);
use Bag\Collection;
use Bag\Exceptions\ImmutableCollectionException;
use Tests\Fixtures\Collections\WrappedCollection;

covers(Collection::class);

test('it clones on forget', function () {
    $collection = Collection::make(['foo' => 'bar', 'baz' => 'bat']);
    $newCollection = $collection->forget('foo');

    expect($collection)->not->toBe($newCollection)
        ->and($collection->all())->toBe(['foo' => 'bar', 'baz' => 'bat'])
        ->and($newCollection->all())->toBe(['baz' => 'bat']);
});

test('it clones on pop', function () {
    $collection = Collection::make(['foo' => 'bar', 'baz' => 'bat']);
    $newCollection = $collection->pop();

    expect($collection->all())->toBe(['foo' => 'bar', 'baz' => 'bat'])
        ->and($newCollection)->toBe('bat');
});

test('it clones on prepend', function () {
    $collection = Collection::make(['baz' => 'bat']);
    $newCollection = $collection->prepend('bar', 'foo');

    expect($collection->all())->toBe(['baz' => 'bat'])
        ->and($newCollection->all())->toBe(['foo' => 'bar', 'baz' => 'bat']);
});

test('it clones on push', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $newCollection = $collection->push('baz');

    expect($collection->all())->toBe(['foo' => 'bar'])
        ->and($newCollection->all())->toBe(['foo' => 'bar', 0 => 'baz']);
});

test('it clones on put', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $newCollection = $collection->put('baz', 'bat');

    expect($collection->all())->toBe(['foo' => 'bar'])
        ->and($newCollection->all())->toBe(['foo' => 'bar', 'baz' => 'bat']);
});

test('it throws exception on pull', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Method pull is not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    $collection->pull('foo');
});

test('it throws exception on shift', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Method shift is not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    $collection->shift(1);
});

test('it throws exception on splice', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Method splice is not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    $collection->splice(0);
});

test('it throws exception on transform', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Method transform is not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    $collection->transform(fn ($item) => $item);
});

test('it throws exception on get or put', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Method getOrPut is not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    $collection->getOrPut('foo', fn () => 'bar');
});

test('it throws exception on offset set', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Array key writes not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    $collection['bar'] = 'bat';
});

test('it throws exception on offset unset', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Array key writes not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    unset($collection['foo']);
});

test('it throws exception on property write', function () {
    $this->expectException(ImmutableCollectionException::class);
    $this->expectExceptionMessage('Property writes not allowed on Bag\Collection');
    $collection = Collection::make(['foo' => 'bar']);
    $collection->baz = 'bat';
});

test('it returns array', function () {
    $collection = Collection::make(['foo' => 'bar']);
    expect($collection->toArray())->toBe(['foo' => 'bar']);
});

test('it returns json', function () {
    $collection = Collection::make(['foo' => 'bar']);
    expect($collection->toJson())->toBe('{"foo":"bar"}');
});

test('it returns unwrapped', function () {
    $collection = WrappedCollection::make(['foo' => 'bar']);
    expect($collection->unwrapped())->toBe(['foo' => 'bar']);
});

test('it can be var_exported', function () {
    $collection = Collection::make(['foo' => 'bar']);

    $exported = var_export($collection, true);

    expect($exported)->toBe(
        <<<EOS
        \Bag\Collection::__set_state(array(
           'items' => 
          array (
            'foo' => 'bar',
          ),
           'escapeWhenCastingToString' => false,
        ))
        EOS
    );

    $imported = eval('return ' . $exported . ';');

    expect($imported)->toBeInstanceOf(Collection::class)
        ->and($imported->toArray())->toBe($collection->toArray());
});
