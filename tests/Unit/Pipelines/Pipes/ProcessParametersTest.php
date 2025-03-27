<?php

declare(strict_types=1);
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Tests\Fixtures\Values\NoConstructorBag;
use Tests\Fixtures\Values\NoPropertiesBag;
use Tests\Fixtures\Values\TestBag;

covers(ProcessParameters::class);

test('it requires a constructor', function () {
    $input = new BagInput(NoConstructorBag::class, collect(['foo' => 'bar']));

    $pipe = new ProcessParameters();
    $pipe($input);
})->throws(\RuntimeException::class, 'Bag "Tests\Fixtures\Values\NoConstructorBag" must have a constructor with at least one parameter');

test('it requires bag parameters', function () {
    $input = new BagInput(NoPropertiesBag::class, collect(['foo' => 'bar']));

    $pipe = new ProcessParameters();
    $pipe($input);
})->throws(\RuntimeException::class, 'Bag "Tests\Fixtures\Values\NoPropertiesBag" must have a constructor with at least one parameter');

test('it handles parameters', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]));

    $pipe = new ProcessParameters();
    $input = $pipe($input);

    expect($input->params)
        ->toBeInstanceOf(ValueCollection::class)
        ->toContainOnlyInstancesOf(Value::class)
        ->toHaveCount(3);
});
