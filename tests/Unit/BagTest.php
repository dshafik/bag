<?php

declare(strict_types=1);

use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Exceptions\MissingPropertiesException;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\Fixtures\BagWithCollection;
use Tests\Fixtures\CastsDateBag;
use Tests\Fixtures\CastsDateInputBag;
use Tests\Fixtures\CastsDateOutputBag;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\HiddenJsonPropertiesBag;
use Tests\Fixtures\HiddenPropertiesBag;
use Tests\Fixtures\MappedInputNameClassBag;
use Tests\Fixtures\MappedNameClassBag;
use Tests\Fixtures\MappedOutputNameClassBag;
use Tests\Fixtures\NoConstructorBag;
use Tests\Fixtures\NoPropertiesBag;
use Tests\Fixtures\TestBag;
use Tests\Fixtures\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\ValidateUsingAttributesBag;
use Tests\Fixtures\ValidateUsingRulesMethodBag;
use Tests\Fixtures\VariadicBag;

uses(WithFaker::class);

it('requires a constructor', function () {
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Bag "Tests\Fixtures\NoConstructorBag" must have a constructor with at least one property');

    NoConstructorBag::from(['foo' => 'bar']);
});

it('requires bag properties', function () {
    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Bag "Tests\Fixtures\NoPropertiesBag" must have a constructor with at least one property');

    NoPropertiesBag::from(['foo' => 'bar']);
});

it('creates value from array', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);

    expect($value->name)->toBe('Davey Shafik');
    expect($value->age)->toBe(40);
    expect($value->email)->toBe('davey@php.net');
});

it('is arrayable', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ])->toArray();

    expect($value['name'])->toBe('Davey Shafik');
    expect($value['age'])->toBe(40);
    expect($value['email'])->toBe('davey@php.net');
});

it('creates new instance using with', function () {
    $value = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 39,
        'email' => 'davey@php.net',
    ]);

    $value2 = $value->with(age: 40);

    $this->assertNotSame($value, $value2);
    expect($value2->name)->toBe('Davey Shafik');
    expect($value2->age)->toBe(40);
    expect($value2->email)->toBe('davey@php.net');
});

it('ignores hidden properties', function () {
    $value = HiddenPropertiesBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);

    expect($value->toArray())->toBe(['name' => 'Davey Shafik', 'age' => 40]);
});

it('maps names', function () {
    $value = MappedNameClassBag::from([
        'name_goes_here' => 'Davey Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]);

    expect($value->nameGoesHere)->toBe('Davey Shafik');
    expect($value->ageGoesHere)->toBe(40);
    expect($value->emailGoesHere)->toBe('davey@php.net');

    expect($value->toArray())->toBe([
        'name_goes_here' => 'Davey Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]);
});

it('maps input names', function () {
    $value = MappedInputNameClassBag::from([
        'name_goes_here' => 'Davey Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]);

    expect($value->nameGoesHere)->toBe('Davey Shafik');
    expect($value->ageGoesHere)->toBe(40);
    expect($value->emailGoesHere)->toBe('davey@php.net');

    expect($value->toArray())->toBe([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);
});

it('maps output names', function () {
    $value = MappedOutputNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);

    expect($value->nameGoesHere)->toBe('Davey Shafik');
    expect($value->ageGoesHere)->toBe(40);
    expect($value->emailGoesHere)->toBe('davey@php.net');

    expect($value->toArray())->toBe([
        'name_goes_here' => 'Davey Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]);
});

it('allows original names', function () {
    $value = MappedNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);

    expect($value->nameGoesHere)->toBe('Davey Shafik');
    expect($value->ageGoesHere)->toBe(40);
    expect($value->emailGoesHere)->toBe('davey@php.net');
});

it('ignores hidden properties in json', function () {
    $value = HiddenJsonPropertiesBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
        'passwordGoesHere' => 'hunter2',
    ]);

    expect(json_encode($value))->toBe('{"name_goes_here":"Davey Shafik","age_goes_here":40}');
    expect($value->toJson())->toBe('{"name_goes_here":"Davey Shafik","age_goes_here":40}');
});

it('errors on additional properties', function () {
    $this->expectException(AdditionalPropertiesException::class);
    $this->expectExceptionMessage('Additional properties found: foo, baz');

    TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
        'foo' => 'bar',
        'baz' => 'bat',
    ]);
});

it('errors on missing properties', function () {
    $this->expectException(MissingPropertiesException::class);
    $this->expectExceptionMessage('Missing required properties: age, email');

    TestBag::from([
        'name' => 'Davey Shafik',
    ]);
});

it('casts input and output', function () {
    $value = CastsDateBag::from(['date' => '2024-04-12 12:34:56']);

    expect($value->date->format('Y-m-d'))->toBe('2024-04-12');
    expect($value->toArray()['date'])->toBe('2024-04-12');
});

it('casts input', function () {
    $value = CastsDateInputBag::from(['date' => '2024-04-12 12:34:56']);

    expect($value->date->format('Y-m-d'))->toBe('2024-04-12');
    expect($value->toArray()['date'])->toBeInstanceOf(CarbonImmutable::class);
});

it('casts output', function () {
    $value = CastsDateOutputBag::from(['date' => new CarbonImmutable('2024-04-12 12:34:56')]);

    expect($value->date->format('Y-m-d'))->toBe('2024-04-12');
    expect($value->toArray()['date'])->toBe('2024-04-12');
});

it('supports variadic properties', function () {
    $value = VariadicBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'foo' => 'bar',
        'baz' => 'bat',
        'bing' => 123,
    ]);

    expect($value->name)->toBe('Davey Shafik');
    expect($value->age)->toBe(40);
    expect($value->extra)->toBe(['foo' => 'bar', 'baz' => 'bat', 'bing' => 123]);
});

it('validates', function () {
    expect(ValidateUsingRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])))->toBeTrue();
});

it('fails validation', function () {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

    try {
        ValidateUsingRulesMethodBag::validate(collect(['name' => 1234]));
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
});

it('validates using rules method', function () {
    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('The age field must be an integer.');

    ValidateUsingRulesMethodBag::from(['name' => 'Davey Shafik', 'age' => 'test string']);
});

it('validates using attributes', function () {
    expect(ValidateUsingAttributesBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])))->toBeTrue();
});

it('validates using both', function () {
    expect(ValidateUsingAttributesAndRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])))->toBeTrue();
});

it('creates custom collections', function () {
    $data = [
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
    ];

    $collection = BagWithCollection::collect($data);

    expect($collection)->toBeInstanceOf(BagWithCollectionCollection::class);
    expect($collection)->toHaveCount(2);
    $collection->each(function (BagWithCollection $bag, $index) use ($data) {
        expect($bag->name)->toBe($data[$index]['name']);
        expect($bag->age)->toBe($data[$index]['age']);
    });
});
