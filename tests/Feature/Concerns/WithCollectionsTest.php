<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Collection;
use Bag\Concerns\WithCollections;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\BagWithCollection;

#[CoversClass(WithCollections::class)]
#[CoversClass(Collection::class)]
#[CoversClass(\Bag\Attributes\Collection::class)]
class WithCollectionsTest extends TestCase
{
    use WithFaker;

    public function testItCreatesCustomCollections()
    {
        $data = [
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ];

        $collection = BagWithCollection::collect($data);

        $this->assertInstanceOf(BagWithCollectionCollection::class, $collection);
        $this->assertCount(2, $collection);
        $collection->each(function (BagWithCollection $bag, $index) use ($data) {
            $this->assertSame($data[$index]['name'], $bag->name);
            $this->assertSame($data[$index]['age'], $bag->age);
        });
    }
    public function testItUsesCache()
    {
        $data = [
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ];

        $collection = BagWithCollection::collect($data);

        $this->assertInstanceOf(BagWithCollectionCollection::class, $collection);
        $this->assertCount(2, $collection);
        $collection->each(function (BagWithCollection $bag, $index) use ($data) {
            $this->assertSame($data[$index]['name'], $bag->name);
            $this->assertSame($data[$index]['age'], $bag->age);
        });

        $collection = BagWithCollection::collect($data);

        $this->assertInstanceOf(BagWithCollectionCollection::class, $collection);
        $this->assertCount(2, $collection);
        $collection->each(function (BagWithCollection $bag, $index) use ($data) {
            $this->assertSame($data[$index]['name'], $bag->name);
            $this->assertSame($data[$index]['age'], $bag->age);
        });
    }
}
