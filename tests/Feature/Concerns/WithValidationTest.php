<?php

declare(strict_types=1);

use Bag\Attributes\Validation\Exists;
use Bag\Attributes\Validation\Unique;
use Bag\Concerns\WithValidation;
use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Internal\Cache;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Models\TestModel;
use Tests\Fixtures\Values\ComputedPropertyBag;
use Tests\Fixtures\Values\ComputedPropertyMissingBag;
use Tests\Fixtures\Values\OptionalValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateExistsRuleBag;
use Tests\Fixtures\Values\ValidateMappedNameClassBag;
use Tests\Fixtures\Values\ValidateUniqueRuleBag;
use Tests\Fixtures\Values\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\Fixtures\Values\ValidateUsingRulesMethodBag;

covers(
    WithValidation::class,
    Unique::class,
    Exists::class,
);

test('it validates', function () {
    expect(ValidateUsingRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])))->toBeTrue();
});

test('it fails validation', function () {
    try {
        ValidateUsingRulesMethodBag::validate(collect(['name' => 1234, 'age' => 'string']));
    } catch (ValidationException $e) {
        expect($e->errors())->toEqual([
            'name' => [
                'The name field must be a string.',
            ],
            'age' => [
                'The age field must be an integer.',
            ],
        ]);

        throw $e;
    }
})->throws(ValidationException::class, 'The name field must be a string. (and 1 more error)');

test('it validates using rules method', function () {
    ValidateUsingRulesMethodBag::from(['name' => 'Davey Shafik', 'age' => 'test string']);
})->throws(ValidationException::class, 'The age field must be an integer.');

test('it validates using attributes', function () {
    expect(ValidateUsingAttributesBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])))->toBeTrue();
});

test('it validates using both', function () {
    expect(ValidateUsingAttributesAndRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])))->toBeTrue();
});

test('it validates mapped names', function () {
    expect(ValidateMappedNameClassBag::validate(collect(['nameGoesHere' => 'Davey Shafik', 'ageGoesHere' => 40])))->toBeTrue();
});

test('it errors without initialized computed property', function () {
    ComputedPropertyMissingBag::from(['name' => 'Davey Shafik', 'age' => 40]);
})->throws(ComputedPropertyUninitializedException::class, 'Property Tests\Fixtures\Values\ComputedPropertyMissingBag->dob must be computed');

test('it validates computed properties', function () {
    Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

    $bag = ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
    expect($bag->dob->format('Y-m-d'))->toBe('1984-05-04');
});

test('it uses cache for computed properties', function () {
    Cache::fake()->shouldReceive('store')->atLeast()->twice()->passthru();

    Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

    ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
    ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
});

test('it supports unique validator', function () {
    TestModel::create(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    expect(
        ValidateUniqueRuleBag::validate(collect(['name' => 'Test User', 'age' => 40, 'email' => 'davey@example.net']))
    )->toBeTrue();

    ValidateUniqueRuleBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']));
})->throws(ValidationException::class, 'The name has already been taken.');

test('it supports exists validator', function () {
    TestModel::create(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    expect(
        ValidateExistsRuleBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']))
    )->toBeTrue();

    ValidateExistsRuleBag::validate(collect(['name' => 'Test User', 'age' => 40, 'email' => 'davey@php.net']));
})->throws(ValidationException::class, 'The selected name is invalid.');

test('it creates without validation', function () {
    $bag = OptionalValidateUsingAttributesAndRulesMethodBag::withoutValidation(['name' => 'Davey Shafik']);
    expect($bag)
        ->toBeInstanceOf(OptionalValidateUsingAttributesAndRulesMethodBag::class)
    ->and($bag->toArray())
        ->toBe(['name' => 'Davey Shafik', 'age' => null]);
});

test('it appends without validation', function () {
    $bag = OptionalValidateUsingAttributesAndRulesMethodBag::withoutValidation(['name' => 'Davey Shafik']);

    expect($bag)
        ->toBeInstanceOf(OptionalValidateUsingAttributesAndRulesMethodBag::class)
    ->and($bag = $bag->append(age: 40))
        ->toBeInstanceOf(OptionalValidateUsingAttributesAndRulesMethodBag::class)
    ->and($bag->toArray())
        ->toBe(['name' => 'Davey Shafik', 'age' => 40])
    ->and($bag = $bag->append(name: 'Another Name')->append(age: 41))
        ->toBeInstanceOf(OptionalValidateUsingAttributesAndRulesMethodBag::class)
    ->and($bag->toArray())
        ->toBe(['name' => 'Another Name', 'age' => 41]);
});

test('it validates and throws exception', function () {
    $bag = OptionalValidateUsingAttributesAndRulesMethodBag::withoutValidation(['name' => 'Davey Shafik']);

    expect(fn () => $bag->valid())
         ->toThrow(ValidationException::class);
});

test('it validates and swallows exception', function () {
    $bag = OptionalValidateUsingAttributesAndRulesMethodBag::withoutValidation(['name' => 'Davey Shafik']);

    expect($bag->valid(false))
        ->toBeNull();
});

test('it validates current instance', function () {
    $bag = OptionalValidateUsingAttributesAndRulesMethodBag::withoutValidation(['name' => 'Davey Shafik']);

    expect($bag = $bag->append(age: 40)->valid())
        ->toBeInstanceOf(OptionalValidateUsingAttributesAndRulesMethodBag::class)
    ->and($bag->toArray())
        ->toBe(['name' => 'Davey Shafik', 'age' => 40]);
});
