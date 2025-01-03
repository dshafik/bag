<?php

declare(strict_types=1);

use Bag\Attributes\WithoutValidation;
use Bag\BagServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Tests\Fixtures\Values\OptionalPropertiesBag;
use Tests\Fixtures\Values\TestBag;

covers(BagServiceProvider::class);

test('it resolves value from request', function () {
    $this->instance('request', Request::createFromBase(new SymfonyRequest(
        server: ['CONTENT_TYPE' => 'application/json'],
        content: json_encode(
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            JSON_THROW_ON_ERROR
        ),
    )));

    $value = resolve(TestBag::class);
    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');
});

test('it resolves value from request multiple times', function () {
    $this->instance('request', Request::createFromBase(new SymfonyRequest(
        server: ['CONTENT_TYPE' => 'application/json'],
        content: json_encode(
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            JSON_THROW_ON_ERROR
        ),
    )));

    $value = resolve(TestBag::class);
    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');

    $value = resolve(TestBag::class);
    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');
});

test('it resolves without validation using closure route', function () {
    $resolved = null;

    Route::post('/test', function (
        #[WithoutValidation]
        OptionalPropertiesBag $bag
    ) use (&$resolved) {
        $resolved = $bag;

        return $bag;
    });

    $this->post('/test', ['name' => 'Davey Shafik']);

    expect($resolved)
        ->toBeInstanceOf(OptionalPropertiesBag::class)
        ->and($resolved->toArray())
        ->toBe(['name' => 'Davey Shafik', 'age' => null, 'email' => null, 'bag' => null]);
});

test('it resolves without validation using class method route', function () {
    class Test
    {
        public static OptionalPropertiesBag $resolved;

        public function test(
            #[WithoutValidation]
            OptionalPropertiesBag $bag
        ) {
            static::$resolved = $bag;

            return $bag;
        }
    }

    Route::post('/test', [\Test::class, 'test']);

    $this->post('/test', ['name' => 'Davey Shafik']);

    expect(\Test::$resolved)
        ->toBeInstanceOf(OptionalPropertiesBag::class)
        ->and(\Test::$resolved->toArray())
        ->toBe(['name' => 'Davey Shafik', 'age' => null, 'email' => null, 'bag' => null]);
});
