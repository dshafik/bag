<?php

declare(strict_types=1);
use Bag\Pipelines\ValidationPipeline;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Validation\ValidationException;
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
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

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
});

test('it validates using attributes', function () {
    $input = new BagInput(ValidateUsingAttributesBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));

    $validation = ValidationPipeline::process($input);

    expect($validation)->toBeTrue();
});

test('it fails validation using attributes', function () {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

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
});

test('it validates using both', function () {
    $input = new BagInput(ValidateUsingAttributesAndRulesMethodBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));

    $validation = ValidationPipeline::process($input);

    expect($validation)->toBeTrue();
});

test('it fails validation using both', function () {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('The name field must not be greater than 100 characters. (and 1 more error)');

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
});
