<?php

declare(strict_types=1);
use Bag\Attributes\Cast;
use Bag\Casts\DateTime;
use Bag\Property\CastInput;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Tests\Fixtures\Values\CastsDateBag;

test('it creates', function () {
    $param = (new \ReflectionClass(CastsDateBag::class))->getConstructor()->getParameters()[0];

    $castInput = CastInput::create($param);

    expect($castInput)->toBeInstanceOf(CastInput::class)
        ->and(property($castInput, 'propertyType'))->toBe(CarbonImmutable::class)
        ->and(property($castInput, 'name'))->toBe('date')
        ->and(property(property($castInput, 'caster'), 'parameters'))->toBe(['format' => 'Y-m-d'])
        ->and(property(property($castInput, 'caster'), 'casterClassname'))->toBe(DateTime::class);
});

test('it casts', function () {
    $caster = $this->createMock(Cast::class);
    $caster->method('cast')
        ->willReturn('castedValue');

    $castInput = new CastInput('string', 'propertyName', $caster);

    $properties = new Collection(['propertyName' => 'propertyValue']);

    expect($castInput->__invoke($properties))->toEqual('castedValue');
});
