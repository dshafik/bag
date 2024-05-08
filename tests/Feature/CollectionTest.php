<?php

declare(strict_types=1);

namespace Tests\Feature;

use Bag\Collection;
use Bag\Exceptions\ImmutableCollectionException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Collections\WrappedCollection;
use Tests\TestCase;

#[CoversClass(Collection::class)]
class CollectionTest extends TestCase
{
    public function testItClonesOnForget(): void
    {
        $collection = Collection::make(['foo' => 'bar', 'baz' => 'bat']);
        $newCollection = $collection->forget('foo');

        $this->assertNotSame($newCollection, $collection);
        $this->assertSame(['foo' => 'bar', 'baz' => 'bat'], $collection->all());
        $this->assertSame(['baz' => 'bat'], $newCollection->all());
    }

    public function testItClonesOnPop(): void
    {
        $collection = Collection::make(['foo' => 'bar', 'baz' => 'bat']);
        $newCollection = $collection->pop();

        $this->assertSame(['foo' => 'bar', 'baz' => 'bat'], $collection->all());
        $this->assertSame('bat', $newCollection);
    }

    public function testItClonesOnPrepend(): void
    {
        $collection = Collection::make(['baz' => 'bat']);
        $newCollection = $collection->prepend('bar', 'foo');

        $this->assertSame(['baz' => 'bat'], $collection->all());
        $this->assertSame(['foo' => 'bar', 'baz' => 'bat'], $newCollection->all());
    }

    public function testItClonesOnPush(): void
    {
        $collection = Collection::make(['foo' => 'bar']);
        $newCollection = $collection->push('baz');

        $this->assertSame(['foo' => 'bar'], $collection->all());
        $this->assertSame(['foo' => 'bar', 0 => 'baz'], $newCollection->all());
    }

    public function testItClonesOnPut(): void
    {
        $collection = Collection::make(['foo' => 'bar']);
        $newCollection = $collection->put('baz', 'bat');

        $this->assertSame(['foo' => 'bar'], $collection->all());
        $this->assertSame(['foo' => 'bar', 'baz' => 'bat'], $newCollection->all());
    }

    public function testItThrowsExceptionOnPull(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Method pull is not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        $collection->pull('foo');
    }

    public function testItThrowsExceptionOnShift(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Method shift is not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        $collection->shift(1);
    }

    public function testItThrowsExceptionOnSplice(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Method splice is not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        $collection->splice(0);
    }

    public function testItThrowsExceptionOnTransform(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Method transform is not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        $collection->transform(fn ($item) => $item);
    }

    public function testItThrowsExceptionOnGetOrPut(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Method getOrPut is not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        $collection->getOrPut('foo', fn () => 'bar');
    }

    public function testItThrowsExceptionOnOffsetSet(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Array key writes not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        $collection['bar'] = 'bat';
    }

    public function testItThrowsExceptionOnOffsetUnset(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Array key writes not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        unset($collection['foo']);
    }

    public function testItThrowsExceptionOnPropertyWrite(): void
    {
        $this->expectException(ImmutableCollectionException::class);
        $this->expectExceptionMessage('Property writes not allowed on Bag\Collection');
        $collection = Collection::make(['foo' => 'bar']);
        $collection->baz = 'bat';
    }

    public function testItReturnsArray()
    {
        $collection = Collection::make(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $collection->toArray());
    }

    public function testItReturnsJson()
    {
        $collection = Collection::make(['foo' => 'bar']);
        $this->assertSame('{"foo":"bar"}', $collection->toJson());
    }

    public function testItReturnsUnwrapped()
    {
        $collection = WrappedCollection::make(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $collection->unwrapped());
    }
}
