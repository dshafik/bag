<?php

declare(strict_types=1);

use Bag\Attributes\Transforms;
use Bag\Pipelines\Pipes\Transform;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Tests\Fixtures\Models\AlternativeTestModel;
use Tests\Fixtures\Models\TestModel;
use Tests\Fixtures\Values\BagWithArrayTransformer;
use Tests\Fixtures\Values\BagWithFactory;
use Tests\Fixtures\Values\BagWithTransformers;

covers(Transform::class, Transforms::class);

test('it transforms from std class', function () {
    $values = (object) ['name' => 'Davey Shafik', 'age' => '40'];
    $input = new BagInput(BagWithTransformers::class, collect([$values]));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe('40')
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it transforms from json string', function () {
    $values = '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}';

    $input = new BagInput(BagWithTransformers::class, collect($values));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it does not transform with array input', function () {
    $values = ['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net'];

    $input = new BagInput(BagWithTransformers::class, collect($values));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it does transforms with positional array argument', function () {
    $values = [['name' => 'Davey Shafik', 'age' => 40]];

    $input = new BagInput(BagWithArrayTransformer::class, collect($values));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it transforms from specific class', function () {
    $values = TestModel::make(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    $input = new BagInput(BagWithTransformers::class, collect($values));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it transforms from child class', function () {
    $values = AlternativeTestModel::make(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    $input = new BagInput(BagWithTransformers::class, collect([$values]));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net')
        ->and($input->input->get('type'))->toBe(Model::class);
});

test('it transforms with multiple transformers', function () {
    $input = new BagInput(BagWithTransformers::class, collect(['Davey Shafik']));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');

    $input = new BagInput(BagWithTransformers::class, collect([40]));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it transforms multiple with single transformer', function () {
    $input = new BagInput(BagWithTransformers::class, collect([['name' => 'Davey Shafik', 'age' => 40]]));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');

    $input = new BagInput(BagWithTransformers::class, collect([BagWithFactory::factory()->make(['name' => 'Davey Shafik', 'age' => 40])]));

    $pipe = new Transform();
    $input = $pipe($input);

    expect($input->input)->toBeInstanceOf(Collection::class)
        ->and($input->input->get('name'))->toBe('Davey Shafik')
        ->and($input->input->get('age'))->toBe(40)
        ->and($input->input->get('email'))->toBe('davey@php.net');
});

test('it errors with invalid type', function () {
    $input = new BagInput(BagWithTransformers::class, collect([1.0]));

    $pipe = new Transform();
    $pipe($input);
})->throws(\TypeError::class, 'Tests\Fixtures\Values\BagWithTransformers::from(): Argument #1 ($values): must be of type ArrayAccess|Traversable|Collection|LaravelCollection|Arrayable|array, double given');
