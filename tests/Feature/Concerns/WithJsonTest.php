<?php

declare(strict_types=1);

use Bag\Concerns\WithJson;
use Bag\Internal\Cache;
use Tests\Fixtures\Values\HiddenJsonParametersBag;
use Tests\Fixtures\Values\OptionalValueBag;
use Tests\Fixtures\Values\TestBag;

covers(WithJson::class);

test('it encodes json', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);

    expect(json_encode($value))->toBe('{"name":"Davey Shafik","age":40,"email":"davey@php.net"}')
        ->and($value->toJson())->toBe('{"name":"Davey Shafik","age":40,"email":"davey@php.net"}');
});

test('it does not encode optionals', function () {
    $value = OptionalValueBag::from(name: 'Davey Shafik');

    expect(json_encode($value))->toBe('{"name":"Davey Shafik"}')
        ->and($value->toJson())->toBe('{"name":"Davey Shafik"}');
});

test('it uses cache', function () {
    Cache::fake()->shouldReceive('store')->atLeast()->times(2)->passthru();

    $value = HiddenJsonParametersBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
        'passwordGoesHere' => 'hunter2',
    ]);

    expect(json_encode($value))->toBe('{"name_goes_here":"Davey Shafik"}')
        ->and($value->toJson())->toBe('{"name_goes_here":"Davey Shafik"}');
});

test('it transforms from JSON string', function () {
    /** @var TestBag $value */
    $value = TestBag::from('{"name":"Davey Shafik","age":40,"email":"davey@php.net"}');

    expect($value->name)->toBe('Davey Shafik')
        ->and($value->age)->toBe(40)
        ->and($value->email)->toBe('davey@php.net');
});
