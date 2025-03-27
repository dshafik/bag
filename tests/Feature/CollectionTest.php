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
    $collection = Collection::make(['foo' => 'bar']);
    $collection->pull('foo');
})->throws(ImmutableCollectionException::class, 'Method pull is not allowed on Bag\Collection');

test('it throws exception on shift', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $collection->shift(1);
})->throws(ImmutableCollectionException::class, 'Method shift is not allowed on Bag\Collection');

test('it throws exception on splice', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $collection->splice(0);
})->throws(ImmutableCollectionException::class, 'Method splice is not allowed on Bag\Collection');

test('it throws exception on transform', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $collection->transform(fn ($item) => $item);
})->throws(ImmutableCollectionException::class, 'Method transform is not allowed on Bag\Collection');

test('it throws exception on get or put', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $collection->getOrPut('foo', fn () => 'bar');
})->throws(ImmutableCollectionException::class, 'Method getOrPut is not allowed on Bag\Collection');

test('it throws exception on offset set', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $collection['bar'] = 'bat';
})->throws(ImmutableCollectionException::class, 'Array key writes not allowed on Bag\Collection');

test('it throws exception on offset unset', function () {
    $collection = Collection::make(['foo' => 'bar']);
    unset($collection['foo']);
})->throws(ImmutableCollectionException::class, 'Array key writes not allowed on Bag\Collection');

test('it throws exception on property write', function () {
    $collection = Collection::make(['foo' => 'bar']);
    $collection->baz = 'bat';
})->throws(ImmutableCollectionException::class, 'Property writes not allowed on Bag\Collection');

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
