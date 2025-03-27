<?php

declare(strict_types=1);

use Bag\Internal\Validator;
use Illuminate\Validation\ValidationException;

covers(Validator::class);

test('it does not throw when valid', function () {
    Validator::validate(['name' => 'Davey Shafik'], collect(['name' => ['string']]));
})->throwsNoExceptions();

test('it does throw when invalid', function () {
    Validator::validate(['name' => 'Davey Shafik'], collect(['name' => ['int']]));
})->throws(ValidationException::class, 'The name field must be an integer.');

test('it does not throw when valid without facade root', function () {
    \Illuminate\Support\Facades\Validator::setFacadeApplication(null);

    Validator::validate(['name' => 'Davey Shafik'], collect(['name' => ['string']]));
})->throwsNoExceptions();

test('it throws when invalid without facade root', function () {
    \Illuminate\Support\Facades\Validator::setFacadeApplication(null);

    Validator::validate(['name' => 'Davey Shafik'], collect(['name' => ['int']]));
})->throws(ValidationException::class, 'The name field must be an integer.');
