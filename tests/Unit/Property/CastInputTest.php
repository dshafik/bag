<?php

declare(strict_types=1);
use Bag\Attributes\Cast;
use Bag\Casts\DateTime;
use Bag\Collection;
use Bag\Property\CastInput;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection as LaravelCollection;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use Tests\Fixtures\Values\CastsDateBag;

covers(CastInput::class);

test('it creates', function () {
    $param = (new \ReflectionClass(CastsDateBag::class))->getConstructor()->getParameters()[0];

    $castInput = CastInput::create($param);

    expect($castInput)->toBeInstanceOf(CastInput::class)
        ->and(property($castInput, 'propertyTypes')->first())->toBe(CarbonImmutable::class)
        ->and(property($castInput, 'name'))->toBe('date')
        ->and(property(property($castInput, 'caster'), 'parameters'))->toBe(['format' => 'Y-m-d'])
        ->and(property(property($castInput, 'caster'), 'casterClassname'))->toBe(DateTime::class);
});

test('it casts', function () {
    $caster = $this->createMock(Cast::class);
    $caster->method('cast')
        ->willReturn('castedValue');

    $type = Collection::wrap((new ReflectionClosure(fn (string $type) => true))->getParameters()[0]->getType());

    $castInput = new CastInput($type, 'propertyName', $caster);

    $properties = new LaravelCollection(['propertyName' => 'propertyValue']);

    expect($castInput->__invoke($properties))->toEqual('castedValue');
});
