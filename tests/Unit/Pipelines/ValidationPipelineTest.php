<?php

declare(strict_types=1);
use Bag\Pipelines\ValidationPipeline;
use Bag\Pipelines\Values\BagInput;
use Bag\Values\Optional;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\Values\OptionalBagWithValidation;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\Fixtures\Values\ValidateUsingRulesMethodBag;

covers(ValidationPipeline::class);

test('it validates without rules', function () {
    $input = new BagInput(TestBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]));

    $validation = ValidationPipeline::process($input);

    expect($validation)->toBeTrue();
});

test('it validates using rules', function () {
    $input = new BagInput(ValidateUsingRulesMethodBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));

    $validation = ValidationPipeline::process($input);

    expect($validation)->toBeTrue();
});

test('it fails validation using rules', function () {
    $input = new BagInput(ValidateUsingRulesMethodBag::class, collect([
        'name' => 1234,
        'age' => 'testing',
    ]));

    try {
        ValidationPipeline::process($input);
    } catch (ValidationException $e) {
        expect($e->errors())->toBe([
            'name' => ['The name field must be a string.'],
            'age' => ['The age field must be an integer.']
        ]);

        throw $e;
    }
})->throws(ValidationException::class, 'The name field must be a string. (and 1 more error)');

test('it validates using attributes', function () {
    $input = new BagInput(ValidateUsingAttributesBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));

    $validation = ValidationPipeline::process($input);

    expect($validation)->toBeTrue();
});

test('it fails validation using attributes', function () {
    $input = new BagInput(ValidateUsingAttributesBag::class, collect([
        'name' => 1234,
        'age' => 'testing',
    ]));

    try {
        ValidationPipeline::process($input);
    } catch (ValidationException $e) {
        expect($e->errors())->toBe([
            'name' => ['The name field must be a string.'],
            'age' => ['The age field must be an integer.']
        ]);

        throw $e;
    }
})->throws(ValidationException::class, 'The name field must be a string. (and 1 more error)');

test('it validates using both', function () {
    $input = new BagInput(ValidateUsingAttributesAndRulesMethodBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));

    $validation = ValidationPipeline::process($input);

    expect($validation)->toBeTrue();
});

test('it fails validation using both', function () {
    $input = new BagInput(ValidateUsingAttributesAndRulesMethodBag::class, collect([
        'name' => str_repeat('Davey Shafik', 40),
        'age' => 200,
    ]));

    try {
        ValidationPipeline::process($input);
    } catch (ValidationException $e) {
        expect($e->errors())->toBe([
            'name' => ['The name field must not be greater than 100 characters.'],
            'age' => ['The age field must not be greater than 100.']
        ]);

        throw $e;
    }
})->throws(ValidationException::class, 'The name field must not be greater than 100 characters. (and 1 more error)');

test('it validates optionals with and without OptionalOr', function () {
    $input = new BagInput(OptionalBagWithValidation::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]));

    $result = ValidationPipeline::process($input);

    expect($result)->toBeTrue();
});

test('it fails validation with Optionals without OptionalOr', function () {
    $input = new BagInput(OptionalBagWithValidation::class, collect([
        'name' => 'John Doe',
        'age' => new Optional(),
        'email' => new Optional(),
    ]));

    ValidationPipeline::process($input);
})->throws(ValidationException::class, 'The age field is required.');
