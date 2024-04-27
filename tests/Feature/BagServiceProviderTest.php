<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Tests\Fixtures\TestBag;

it('resolves value from request', function () {
    app()->instance('request', $this->createTestRequest(new Request(
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
