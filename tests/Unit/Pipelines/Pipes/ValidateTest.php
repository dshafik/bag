<?php

declare(strict_types=1);

use Bag\Pipelines\Pipes\FillNulls;
use Bag\Pipelines\Pipes\FillOptionals;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\Validate;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Values\OptionalBagWithValidation;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\ValidateMappedNameClassBag;
use Tests\Fixtures\Values\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\Fixtures\Values\ValidateUsingRulesMethodBag;

covers(Validate::class);

test('it validates', function () {
    $input = new BagInput(ValidateUsingRulesMethodBag::class, collect(['name' => 'Davey Shafik', 'age' => 40]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $input = $pipe($input);

    expect($input)->toBeInstanceOf(BagInput::class);
});

test('it validates with no rules', function () {
    $input = new BagInput(TestBag::class, collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $input = $pipe($input);

    expect($input)->toBeInstanceOf(BagInput::class);
});

test('it fails validation', function () {
    try {
        $input = new BagInput(ValidateUsingRulesMethodBag::class, collect(['name' => 1234]));
        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);
        $input = (new IsVariadic())($input);

        $pipe = new Validate();
        $pipe($input);
    } catch (ValidationException $e) {
        expect($e->errors())->toEqual([
            'name' => [
                'The name field must be a string.',
            ],
            'age' => [
                'The age field is required.',
            ],
        ]);

        throw $e;
    }
})->throws(ValidationException::class, 'The name field must be a string. (and 1 more error)');

test('it validates using rules method', function () {
    $input = new BagInput(ValidateUsingRulesMethodBag::class, collect(['name' => 'Davey Shafik', 'age' => 'test string']));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $pipe($input);
})->throws(ValidationException::class, 'The age field must be an integer.');

test('it validates using attributes', function () {
    $input = new BagInput(ValidateUsingAttributesBag::class, collect(['name' => 'Davey Shafik', 'age' => 'test string']));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $pipe($input);
})->throws(ValidationException::class, 'The age field must be an integer.');

test('it validates using both', function () {
    try {
        $input = new BagInput(ValidateUsingAttributesAndRulesMethodBag::class, collect(['name' => 1234, 'age' => 200]));
        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);
        $input = (new IsVariadic())($input);

        $pipe = new Validate();
        $pipe($input);
    } catch (ValidationException $e) {
        expect($e->errors())->toEqual([
            'name' => [
                'The name field must be a string.',
            ],
            'age' => [
                'The age field must not be greater than 100.',
            ],
        ]);

        throw $e;
    }
})->throws(ValidationException::class, 'The name field must be a string. (and 1 more error)');

test('it validates mapped names', function () {
    try {
        $input = new BagInput(ValidateMappedNameClassBag::class, collect(['nameGoesHere' => 1234]));
        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);
        $input = (new IsVariadic())($input);

        $pipe = new Validate();
        $pipe($input);
    } catch (ValidationException $e) {
        expect($e->errors())->toEqual([
            'nameGoesHere' => [
                'The name goes here field must be a string.',
            ],
            'ageGoesHere' => [
                'The age goes here field is required.',
            ],
        ]);

        throw $e;
    }
})->throws(ValidationException::class, 'The name goes here field must be a string. (and 1 more error)');

test('it validates optional values', function () {
    $input = new BagInput(OptionalBagWithValidation::class, collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new FillOptionals())($input);
    $input = (new FillNulls())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $result = $pipe($input);

    expect($result)->toBeInstanceOf(BagInput::class);
});

test('it fails validation of optional values', function () {
    $input = new BagInput(OptionalBagWithValidation::class, collect(['name' => 'Davey Shafik']));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new FillOptionals())($input);
    $input = (new FillNulls())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $pipe($input);
})->throws(ValidationException::class, 'The age field is required.');

test('it fails validation of optional values with values', function () {
    $input = new BagInput(OptionalBagWithValidation::class, collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'test']));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new FillOptionals())($input);
    $input = (new FillNulls())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $pipe($input);
})->throws(ValidationException::class, 'The email field must be a valid email address.');

test('it passes validation of optional values with null values', function () {
    $input = new BagInput(OptionalBagWithValidation::class, collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => null]));
    $input = (new ProcessParameters())($input);
    $input = (new MapInput())($input);
    $input = (new FillOptionals())($input);
    $input = (new FillNulls())($input);
    $input = (new IsVariadic())($input);

    $pipe = new Validate();
    $result = $pipe($input);

    expect($result)->toBeInstanceOf(BagInput::class);
});
