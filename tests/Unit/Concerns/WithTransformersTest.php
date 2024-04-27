<?php

declare(strict_types=1);

namespace Tests\Unit\Concerns;

use Illuminate\Database\Eloquent\Model;
use Orchestra\Testbench\TestCase;
use Tests\Fixtures\BagWithFactory;
use Tests\Fixtures\BagWithTransformers;
use Tests\Fixtures\Models\AlternativeTestModel;
use Tests\Fixtures\Models\TestModel;
use TypeError;

class WithTransformersTest extends TestCase
{
    public function testItTransformsFromStdClass(): void
    {
        $values = (object) ['name' => 'Davey Shafik', 'age' => '40'];

        $bag = BagWithTransformers::from($values);

        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItTransformsFromJsonString(): void
    {
        $values = '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}';

        $bag = BagWithTransformers::from($values);

        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItDoesNotTransform(): void
    {
        $values = ['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net'];

        $bag = BagWithTransformers::from($values);

        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItTransformsFromSpecificClass(): void
    {
        $values = TestModel::make(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

        $bag = BagWithTransformers::from($values);

        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
        $this->assertSame(TestModel::class, $bag->type);
    }

    public function testItTransformsFromChildClass(): void
    {
        $values = AlternativeTestModel::make(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

        $bag = BagWithTransformers::from($values);

        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
        $this->assertSame(Model::class, $bag->type);
    }

    public function testItTransformsWithMultipleTransformers(): void
    {
        $bag = BagWithTransformers::from('Davey Shafik');
        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);

        $bag = BagWithTransformers::from(40);
        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItTransformsMultipleWithSingleTransformer(): void
    {
        $bag = BagWithTransformers::from(['name' => 'Davey Shafik', 'age' => 40]);
        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);

        $bag = BagWithTransformers::from(BagWithFactory::factory()->make(['name' => 'Davey Shafik', 'age' => 40]));
        $this->assertInstanceOf(BagWithTransformers::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItErrorsWithInvalidType(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Tests\Fixtures\BagWithTransformers::from(): Argument #1 ($values): must be of type ArrayAccess|Traversable|Collection|LaravelCollection|Arrayable|array, double given');

        BagWithTransformers::from(1.0);
    }
}
