<?php

declare(strict_types=1);

namespace Tests\Feature;

use Bag\Attributes\Factory as FactoryAttribute;
use Bag\Collection;
use Bag\Exceptions\MissingFactoryException;
use Bag\Factory;
use Bag\Traits\HasFactory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Collections\BagWithFactoryAndCollectionCollection;
use Tests\Fixtures\Factories\BagWithFactoryFactory;
use Tests\Fixtures\Values\BagWithFactory;
use Tests\Fixtures\Values\BagWithFactoryAndCollection;
use Tests\Fixtures\Values\BagWithInvalidFactoryAttribute;
use Tests\Fixtures\Values\BagWithoutFactoryAttribute;
use Tests\TestCase;

#[CoversClass(Factory::class)]
#[CoversClass(HasFactory::class)]
#[CoversClass(FactoryAttribute::class)]
#[CoversClass(MissingFactoryException::class)]
class FactoryTest extends TestCase
{
    use WithFaker;

    public function testItResolvesFactory()
    {
        $this->assertInstanceOf(BagWithFactoryFactory::class, BagWithFactory::factory());
    }

    public function testItResolvesFactoryUsingCache()
    {
        $this->assertInstanceOf(BagWithFactoryFactory::class, BagWithFactory::factory());
        $this->assertInstanceOf(BagWithFactoryFactory::class, BagWithFactory::factory());
    }

    public function testItMakesWithFactoryDefaultState()
    {
        $bag = BagWithFactory::factory()->make();

        $this->assertInstanceOf(BagWithFactory::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
    }

    public function testItMakesMultipleUsingCountWithDefaultState()
    {
        $bags = BagWithFactory::factory()->count(3)->make();

        $this->assertCount(3, $bags);
        $bags->each(function (BagWithFactory $bag) {
            $this->assertSame('Davey Shafik', $bag->name);
            $this->assertSame(40, $bag->age);
        });
    }

    public function testItMakesMultipleUsingFactoryWithDefaultState()
    {
        $bags = BagWithFactory::factory(3)->make();

        $this->assertInstanceOf(Collection::class, $bags);
        $this->assertCount(3, $bags);
        $bags->each(function (BagWithFactory $bag) {
            $this->assertSame('Davey Shafik', $bag->name);
            $this->assertSame(40, $bag->age);
        });
    }

    public function testItMakesMultipleWithSequences()
    {
        $data = [
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ];

        $bags = BagWithFactory::factory()->count(3)->state(new Sequence(...$data))->make();

        $this->assertInstanceOf(Collection::class, $bags);
        $this->assertCount(3, $bags);
        $bags->each(function (BagWithFactory $bag, $index) use ($data) {
            $this->assertSame($data[$index]['name'], $bag->name);
            $this->assertSame($data[$index]['age'], $bag->age);
        });
    }

    public function testItMakesMultipleWithSequencesAndWrapsAround()
    {
        $data = [
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ];

        $bags = BagWithFactory::factory()->count(6)->state(new Sequence(...$data))->make();

        $data = array_merge($data, $data);

        $this->assertInstanceOf(Collection::class, $bags);
        $this->assertCount(6, $bags);
        $bags->each(function (BagWithFactory $bag, $index) use ($data) {
            $this->assertSame($data[$index]['name'], $bag->name);
            $this->assertSame($data[$index]['age'], $bag->age);
        });
    }

    public function testItUsesCustomBagCollection()
    {
        $bags = BagWithFactoryAndCollection::factory()->count(3)->make();

        $this->assertInstanceOf(BagWithFactoryAndCollectionCollection::class, $bags);
        $this->assertCount(3, $bags);
        $bags->each(function (BagWithFactoryAndCollection $bag) {
            $this->assertSame('Davey Shafik', $bag->name);
            $this->assertSame(40, $bag->age);
        });
    }

    public function testItCreatesUsingSequence()
    {
        $data = [
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ];

        $bags = BagWithFactory::factory()->count(3)->sequence(... $data)->make();

        $this->assertInstanceOf(Collection::class, $bags);
        $this->assertSame($data[0], $bags[0]->toArray());
        $this->assertSame($data[1], $bags[1]->toArray());
        $this->assertSame($data[0], $bags[2]->toArray());
    }

    public function testItErrorsWithoutFactoryAttribute()
    {
        $this->expectException(MissingFactoryException::class);
        $this->expectExceptionMessage('Bag "Tests\Fixtures\Values\BagWithoutFactoryAttribute" missing factory attribute');

        BagWithoutFactoryAttribute::factory();
    }

    public function testItErrorsWithInvalidFactoryAttribute()
    {
        $this->expectException(MissingFactoryException::class);
        $this->expectExceptionMessage('Factory class "InvalidFactoryClass" for Bag "Tests\Fixtures\Values\BagWithInvalidFactoryAttribute" not found');

        BagWithInvalidFactoryAttribute::factory();
    }
}
