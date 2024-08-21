<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Attributes\Collection as CollectionAttribute;
use Bag\Collection;
use Bag\Concerns\WithCollections;
use Bag\Internal\Cache;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\TestCase;

#[CoversTrait(WithCollections::class)]
#[CoversClass(Collection::class)]
#[CoversClass(CollectionAttribute::class)]
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
        Cache::fake()->shouldReceive('store')->atLeast()->twice()->passthru();

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
