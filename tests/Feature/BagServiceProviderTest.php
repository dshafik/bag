<?php

declare(strict_types=1);

use Bag\BagServiceProvider;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
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
