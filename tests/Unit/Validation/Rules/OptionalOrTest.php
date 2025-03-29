<?php

declare(strict_types=1);

use Bag\Validation\Rules\OptionalOr;
use Bag\Values\Optional;
use Illuminate\Support\Facades\Validator;

covers(OptionalOr::class);

test('it validates optionals with types', function () {
    $validator = Validator::make([
        'name' => 'Davey Shafik',
        'age' => new Optional(),
        'email' => new Optional(),
    ], [
        'name' => ['required', 'string'],
        'age' => [new OptionalOr('int')],
        'email' => [new OptionalOr('string')],
    ]);

    expect($validator->passes())->toBeTrue();
});

test('it validates optionals without types', function () {
    $validator = Validator::make([
        'name' => 'Davey Shafik',
        'age' => new Optional(),
        'email' => new Optional(),
    ], [
        'name' => ['required', 'string'],
        'age' => [new OptionalOr()],
        'email' => new OptionalOr(),
    ]);

    expect($validator->passes())->toBeTrue();
});

test('it validates optionals with validation', function () {
    $validator = Validator::make([
        'name' => 'Davey Shafik',
        'age' => new Optional(),
        'email' => new Optional(),
    ], [
        'name' => ['required', 'string'],
        'age' => [new OptionalOr('int')],
        'email' => [new OptionalOr('email')],
    ]);

    expect($validator->passes())->toBeTrue();
});

test('it validates optionals with nulls', function () {
    $validator = Validator::make([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ], [
        'name' => ['required', 'string'],
        'age' => [new OptionalOr('int')],
        'email' => [new OptionalOr(['nullable', 'email'])],
    ]);

    expect($validator->passes())->toBeTrue();
});

test('it validates classnames', function () {
    $validator = Validator::make([
        'date' => new \DateTimeImmutable(),
    ], [
        'date' => [new OptionalOr(\DateTimeImmutable::class)],
    ]);

    expect($validator->passes())->toBeTrue();
});

test('it fails to validate invalid classnames', function () {
    $validator = Validator::make([
        'date' => new \DateTime(),
    ], [
        'date' => [new OptionalOr(\DateTimeImmutable::class)],
    ]);

    expect($validator->passes())->toBeFalse();
});

test('it fails validation for values', function () {
    $validator = Validator::make([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => null,
    ], [
        'name' => ['required', 'string'],
        'age' => [new OptionalOr('int')],
        'email' => [new OptionalOr(['required', 'email'])],
    ]);

    expect($validator->passes())->toBeFalse();
});
