<?php

declare(strict_types=1);

use Bag\Concerns\WithOutput;
use Tests\Fixtures\Values\HiddenParametersBag;
use Tests\Fixtures\Values\MappedOutputNameClassBag;
use Tests\Fixtures\Values\WrappedBag;

covers(WithOutput::class);

test('it maps output names', function () {
    $value = MappedOutputNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);

    expect($value->nameGoesHere)->toBe('Davey Shafik')
        ->and($value->ageGoesHere)->toBe(40)
        ->and($value->emailGoesHere)->toBe('davey@php.net')
        ->and($value->toArray())->toBe([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]);

});

test('it gets values', function () {
    $value = MappedOutputNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net'
    ]);

    $values = $value->get();

    expect($values['nameGoesHere'])->toBe('Davey Shafik')
        ->and($values['ageGoesHere'])->toBe(40)
        ->and($values['emailGoesHere'])->toBe('davey@php.net');
});

test('it gets values without hidden', function () {
    $value = HiddenParametersBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $values = $value->get();

    expect($values['name'])->toBe('Davey Shafik')
        ->and($values)->not->toHaveKey('age')
        ->and($values)->not->toHaveKey('email');
});

test('it gets value', function () {
    $value = MappedOutputNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net'
    ]);

    $name = $value->get('nameGoesHere');

    expect($name)->toBe('Davey Shafik');
});

test('it does not get hidden value', function () {
    $value = HiddenParametersBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $value = $value->get('email');

    expect($value)->toBeNull();
});

test('it gets raw values', function () {
    $value = HiddenParametersBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $values = $value->getRaw();

    expect($values['name'])->toBe('Davey Shafik')
        ->and($values['age'])->toBe(40)
        ->and($values['email'])->toBe('davey@php.net');
});

test('it gets raw value', function () {
    $value = HiddenParametersBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $email = $value->getRaw('email');

    expect($email)->toBe('davey@php.net');
});

test('it gets unwrapped', function () {
    $value = WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    expect($value->unwrapped())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);
});
