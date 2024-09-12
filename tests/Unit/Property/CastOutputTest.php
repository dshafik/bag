<?php

declare(strict_types=1);
use Bag\Attributes\Cast;
use Bag\Casts\DateTime;
use Bag\Property\CastOutput;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Tests\Fixtures\Values\CastsDateBag;

covers(CastOutput::class);

test('it creates', function () {
    $param = (new \ReflectionClass(CastsDateBag::class))->getConstructor()->getParameters()[0];

    $castOutput = CastOutput::create($param);

    expect($castOutput)->toBeInstanceOf(CastOutput::class)
        ->and(property($castOutput, 'propertyType'))->toBe(CarbonImmutable::class)
        ->and(property(property($castOutput, 'caster'), 'parameters'))->toBe(['format' => 'Y-m-d'])
        ->and(property(property($castOutput, 'caster'), 'casterClassname'))->toBe(DateTime::class);
});

test('it casts', function () {
    $caster = $this->createMock(Cast::class);
    $caster->method('transform')
        ->willReturn('castedValue');

    $castOutput = new CastOutput('string', 'propertyName', $caster);

    $properties = new Collection(['propertyName' => 'propertyValue']);

    expect($castOutput->__invoke($properties))->toEqual('castedValue');
});

test('it does not casts', function () {
    $castOutput = new CastOutput('string', 'propertyName', null);

    $properties = new Collection(['propertyName' => 'propertyValue']);

    expect($castOutput->__invoke($properties))->toEqual('propertyValue');
});
