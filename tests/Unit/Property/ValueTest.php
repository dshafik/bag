<?php

declare(strict_types=1);
use Bag\Property\CastInput;
use Bag\Property\CastOutput;
use Bag\Property\ValidatorCollection;
use Bag\Property\Value;
use Tests\Fixtures\Values\ValidateMappedNameClassBag;

covers(Value::class);

test('it creates value from property', function () {
    $class = new \ReflectionClass(ValidateMappedNameClassBag::class);
    $property = $class->getProperty('nameGoesHere');

    $value = Value::create($class, $property);

    expect($value)->toBeInstanceOf(Value::class)
        ->and($value->bag)->toBeInstanceOf(\ReflectionClass::class)
        ->and($value->bag->name)->toBe(ValidateMappedNameClassBag::class)
        ->and($value->property)->toBeInstanceOf(\ReflectionProperty::class)
        ->and($value->property->name)->toBe('nameGoesHere')
        ->and($value->type->first())->toBe('string')
        ->and($value->name)->toBe('nameGoesHere')
        ->and($value->required)->toBeTrue()
        ->and($value->maps->get('input')->toArray())->toBe(['name_goes_here'])
        ->and($value->maps->get('output'))->toBe('name_goes_here')
        ->and($value->inputCast)->toBeInstanceOf(CastInput::class)
        ->and($value->outputCast)->toBeInstanceOf(CastOutput::class)
        ->and($value->validators)->toBeInstanceOf(ValidatorCollection::class)
        ->and($value->validators->all())->toBe(['string', 'required'])
        ->and($value->variadic)->toBeFalse();

});

test('it creates value from parameter', function () {
    $class = new \ReflectionClass(ValidateMappedNameClassBag::class);
    $property = $class->getConstructor()->getParameters()[0];

    $value = Value::create($class, $property);

    expect($value)->toBeInstanceOf(Value::class)
        ->and($value->bag)->toBeInstanceOf(\ReflectionClass::class)
        ->and($value->bag->name)->toBe(ValidateMappedNameClassBag::class)
        ->and($value->property)->toBeInstanceOf(\ReflectionParameter::class)
        ->and($value->property->name)->toBe('nameGoesHere')
        ->and($value->type->first())->toBe('string')
        ->and($value->name)->toBe('nameGoesHere')
        ->and($value->required)->toBeTrue()
        ->and($value->maps->get('input')->toArray())->toBe(['name_goes_here'])
        ->and($value->maps->get('output'))->toBe('name_goes_here')
        ->and($value->inputCast)->toBeInstanceOf(CastInput::class)
        ->and($value->outputCast)->toBeInstanceOf(CastOutput::class)
        ->and($value->validators)->toBeInstanceOf(ValidatorCollection::class)
        ->and($value->validators->all())->toBe(['string', 'required'])
        ->and($value->variadic)->toBeFalse();

});
