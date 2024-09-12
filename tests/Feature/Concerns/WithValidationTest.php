<?php

declare(strict_types=1);

use Bag\Concerns\WithValidation;
use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Internal\Cache;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Values\ComputedPropertyBag;
use Tests\Fixtures\Values\ComputedPropertyMissingBag;
use Tests\Fixtures\Values\ValidateMappedNameClassBag;
use Tests\Fixtures\Values\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\Fixtures\Values\ValidateUsingRulesMethodBag;

covers(WithValidation::class);

test('it validates', function () {
    expect(ValidateUsingRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])))->toBeTrue();
});

test('it fails validation', function () {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

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
});

test('it validates using rules method', function () {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('The age field must be an integer.');

    ValidateUsingRulesMethodBag::from(['name' => 'Davey Shafik', 'age' => 'test string']);
});

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
    $this->expectException(ComputedPropertyUninitializedException::class);
    $this->expectExceptionMessage('Property Tests\Fixtures\Values\ComputedPropertyMissingBag->dob must be computed');

    ComputedPropertyMissingBag::from(['name' => 'Davey Shafik', 'age' => 40]);
});

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
