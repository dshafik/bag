<?php

declare(strict_types=1);

use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Attributes\Laravel\FromRouteParameterProperty;
use Bag\Exceptions\InvalidRouteParameterException;
use Bag\Pipelines\Pipes\CastInputValues;
use Bag\Pipelines\Pipes\LaravelRouteParameters;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Fixtures\Models\CastedModel;
use Tests\Fixtures\Values\BagWithRequestParams;
use Tests\Fixtures\Values\TestBag;

uses(DatabaseTransactions::class);

covers(LaravelRouteParameters::class, FromRouteParameter::class, FromRouteParameterProperty::class);

test('it populates string parameter value', function () {
    $this->get('/string/testing');

    $input = runPipeline();

    expect($input->values->get('stringParam'))->toBe('testing')
        ->and($input->values->get('notNamedStringParam'))->toBe('testing');
});

test('it populates int parameter value', function () {
    $this->get('/int/1234');

    $input = runPipeline();

    expect($input->values->get('intParam'))->toBe(1234)
        ->and($input->values->get('notNamedIntParam'))->toBe(1234);
});

test('it populates model parameter value', function () {
    $model = CastedModel::create([
        'bag' => null,
    ]);

    $this->get('/model/' . $model->id);

    $input = runPipeline();

    expect($input->values->get('modelParam'))->toBeInstanceOf(CastedModel::class)
        ->and($input->values->get('modelParam')->id)->toBe(1)
        ->and($input->values->get('notNamedModelParam'))->toBeInstanceOf(CastedModel::class)
        ->and($input->values->get('notNamedModelParam')->id)->toBe(1);

});

test('it populates model parameter property value', function () {
    $model = CastedModel::create([
        'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
    ]);

    $this->get('/model/' . $model->id);

    $input = runPipeline();

    expect($input->values->get('bag'))->toBeInstanceOf(TestBag::class)
        ->and($input->values->get('bag')->name)->toBe('Davey Shafik')
        ->and($input->values->get('bag')->age)->toBe(40)
        ->and($input->values->get('bag')->email)->toBe('davey@php.net')
        ->and($input->values->get('notNamedBag'))->toBeInstanceOf(TestBag::class)
        ->and($input->values->get('notNamedBag')->name)->toBe('Davey Shafik')
        ->and($input->values->get('notNamedBag')->age)->toBe(40)
        ->and($input->values->get('notNamedBag')->email)->toBe('davey@php.net');

});

test('it errors when trying to get property on scalar value', function () {
    $this->expectException(InvalidRouteParameterException::class);
    $this->expectExceptionMessage('Route parameter "invalidParam" must be an object.');
    $this->get('/invalid/testing');

    runPipeline();
});

test('it ignores unbound params', function () {
    $this->get('/no-binding/testing');

    $input = runPipeline();

    expect($input->values->get('notBound'))->toBeNull();
});

test('it does not override body', function () {
    $this->postJson('/with-bag/overridden', ['stringParam' => 'testing', 'notNamedStringParam' => 'testing']);

    $input = runPipeline();

    expect($input->values->get('stringParam'))->toBe('overridden')
        ->and($input->values->get('notNamedStringParam'))->toBe('overridden');
});

function runPipeline(): BagInput
{
    $input = new BagInput(BagWithRequestParams::class, collect());

    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new LaravelRouteParameters())($input);
    $input = (new CastInputValues())($input);

    return $input;
}
