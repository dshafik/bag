<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Attributes\Transforms;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Models\AlternativeTestModel;
use Tests\Fixtures\Models\TestModel;
use Tests\Fixtures\Values\BagWithFactory;
use Tests\Fixtures\Values\BagWithTransformers;
use Tests\TestCase;
use TypeError;

#[CoversClass(Transform::class)]
#[CoversClass(Transforms::class)]
class TransformTest extends TestCase
{
    public function testItTransformsFromStdClass(): void
    {
        $values = (object) ['name' => 'Davey Shafik', 'age' => '40'];
        $input = new BagInput(BagWithTransformers::class, $values);

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame('40', $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));
    }

    public function testItTransformsFromJsonString(): void
    {
        $values = '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}';

        $input = new BagInput(BagWithTransformers::class, $values);

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));
    }

    public function testItDoesNotTransform(): void
    {
        $values = ['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net'];

        $input = new BagInput(BagWithTransformers::class, $values);

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));
    }

    public function testItTransformsFromSpecificClass(): void
    {
        $values = TestModel::make(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

        $input = new BagInput(BagWithTransformers::class, $values);

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));
    }

    public function testItTransformsFromChildClass(): void
    {
        $values = AlternativeTestModel::make(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

        $input = new BagInput(BagWithTransformers::class, $values);

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));
        $this->assertSame(Model::class, $input->input->get('type'));
    }

    public function testItTransformsWithMultipleTransformers(): void
    {
        $input = new BagInput(BagWithTransformers::class, 'Davey Shafik');

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));

        $input = new BagInput(BagWithTransformers::class, 40);

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));
    }

    public function testItTransformsMultipleWithSingleTransformer(): void
    {
        $input = new BagInput(BagWithTransformers::class, ['name' => 'Davey Shafik', 'age' => 40]);

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));

        $input = new BagInput(BagWithTransformers::class, BagWithFactory::factory()->make(['name' => 'Davey Shafik', 'age' => 40]));

        $pipe = new Transform();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(Collection::class, $input->input);
        $this->assertSame('Davey Shafik', $input->input->get('name'));
        $this->assertSame(40, $input->input->get('age'));
        $this->assertSame('davey@php.net', $input->input->get('email'));
    }

    public function testItErrorsWithInvalidType(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Tests\Fixtures\Values\BagWithTransformers::from(): Argument #1 ($values): must be of type ArrayAccess|Traversable|Collection|LaravelCollection|Arrayable|array, double given');

        $input = new BagInput(BagWithTransformers::class, 1.0);

        $pipe = new Transform();
        $pipe($input, fn ($input) => $input);
    }
}
