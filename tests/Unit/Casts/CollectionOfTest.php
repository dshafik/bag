<?php

declare(strict_types=1);

namespace Tests\Unit\Casts;

use Bag\Casts\CollectionOf;
use Bag\Collection;
use Bag\Exceptions\InvalidCollection;
use Illuminate\Support\Collection as LaravelCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use stdClass;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(CollectionOf::class)]
class CollectionOfTest extends TestCase
{
    public function testItCreatesLaravelCollectionOfBags()
    {
        $cast = new CollectionOf(TestBag::class);
        $collection = $cast->set(LaravelCollection::class, 'test', collect(['test' => [
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ]]));

        $this->assertInstanceOf(LaravelCollection::class, $collection);
        $this->assertContainsOnlyInstancesOf(TestBag::class, $collection);
        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ], $collection->toArray());
    }

    public function testItCreatesCollectionOfBags()
    {
        $cast = new CollectionOf(TestBag::class);
        $collection = $cast->set(Collection::class, 'test', collect(['test' => [
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ]]));

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertContainsOnlyInstancesOf(TestBag::class, $collection);
        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ], $collection->toArray());
    }

    public function testItCreatesCustomCollectionOfBags()
    {
        $cast = new CollectionOf(TestBag::class);
        $collection = $cast->set(BagWithCollectionCollection::class, 'test', collect(['test' => [
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ]]));

        $this->assertInstanceOf(BagWithCollectionCollection::class, $collection);
        $this->assertContainsOnlyInstancesOf(TestBag::class, $collection);
        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ], $collection->toArray());
    }

    public function testItFailsWithInvalidCollection()
    {
        $this->expectException(InvalidCollection::class);
        $this->expectExceptionMessage('The property "test" must be a subclass of Illuminate\Support\Collection');

        $cast = new CollectionOf(TestBag::class);
        $cast->set(stdClass::class, 'test', collect(['test' => [
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ]]));
    }
}
