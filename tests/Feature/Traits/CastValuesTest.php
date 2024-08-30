<?php

declare(strict_types=1);
use Carbon\CarbonImmutable;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\Fixtures\Values\CastInputOutputBag;
use Tests\Fixtures\Values\CastsDateBag;
use Tests\Fixtures\Values\CastsDateInputBag;
use Tests\Fixtures\Values\CastsDateOutputBag;
use Tests\Fixtures\Values\CastVariadicCollectionBag;
use Tests\Fixtures\Values\CastVariadicDatetimeBag;
use Tests\Fixtures\Values\TypedVariadicBag;
use Tests\Fixtures\Values\VariadicBag;

test('it does not cast input', function () {
    $value = CastInputOutputBag::from([
        'input' => 'test',
        'output' => 'testing',
    ]);

    expect($value->input->toString())->toBe('TEST')
        ->and($value->output)->toBe('testing')
        ->and($value->toArray()['output'])->toBe('TESTING');
});

test('it casts input and output', function () {
    $value = CastsDateBag::from(['date' => '2024-04-12 12:34:56']);

    expect($value->date->format('Y-m-d'))->toBe('2024-04-12')
        ->and($value->toArray()['date'])->toBe('2024-04-12');
});

test('it casts input', function () {
    $value = CastsDateInputBag::from(['date' => '2024-04-12 12:34:56']);

    expect($value->date->format('Y-m-d'))->toBe('2024-04-12')
        ->and($value->toArray()['date'])->toBeInstanceOf(CarbonImmutable::class);
});

test('it casts output', function () {
    $value = CastsDateOutputBag::from(['date' => new CarbonImmutable('2024-04-12 12:34:56')]);

    expect($value->date->format('Y-m-d'))->toBe('2024-04-12')
        ->and($value->toArray()['date'])->toBe('2024-04-12');
});

test('it does not cast mixed variadics', function () {
    $value = VariadicBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'extra' => true,
    ]);

    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->values['extra'])->toBeTrue();
});

test('it casts typed variadics', function () {
    $value = TypedVariadicBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'extra' => 1,
    ]);

    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->values['extra'])->toBeTrue();
});

test('is casts variadics collections', function () {
    $extra = [
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
        ['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)],
    ];
    $more = [['name' => fake()->name(), 'age' => fake()->numberBetween(18, 100)]];
    $value = CastVariadicCollectionBag::from([
        'name' => 'Davey Shafik',
        'age' => '40',
        'extra' => $extra,
        'more' => $more,
    ]);

    expect($value->values['extra'])->toContainOnlyInstancesOf(BagWithCollection::class)
        ->and($value->values['extra'])->toHaveCount(2)
        ->and($value->values['more'])->toContainOnlyInstancesOf(BagWithCollection::class)
        ->and($value->values['more'])->toHaveCount(1)
        ->and($value->values['extra']->toArray())->toBe($extra)
        ->and($value->values['more']->toArray())->toBe($more);

});

test('is casts variadics datetime', function () {
    $value = CastVariadicDatetimeBag::from([
        'name' => 'Davey Shafik',
        'age' => '40',
        'extra' => '2024-04-30',
        'more' => '2024-05-31',
    ]);

    expect($value->values)->toContainOnlyInstancesOf(CarbonImmutable::class)
        ->and($value->values)->toHaveCount(2)
        ->and($value->values['extra']->format('Y-m-d'))->toBe('2024-04-30')
        ->and($value->values['more']->format('Y-m-d'))->toBe('2024-05-31');

});
