<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Exceptions\MissingPropertiesException;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Orchestra\Testbench\TestCase;
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

class BagTest extends TestCase
{
    use WithFaker;

    public function testItRequiresAConstructor()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Bag "Tests\Fixtures\NoConstructorBag" must have a constructor with at least one property');

        NoConstructorBag::from(['foo' => 'bar']);
    }

    public function testItRequiresBagProperties()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Bag "Tests\Fixtures\NoPropertiesBag" must have a constructor with at least one property');

        NoPropertiesBag::from(['foo' => 'bar']);
    }

    public function testItCreatesValueFromArray()
    {
        $value = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->name);
        $this->assertSame(40, $value->age);
        $this->assertSame('davey@php.net', $value->email);
    }

    public function testItIsArrayable()
    {
        $value = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ])->toArray();

        $this->assertSame('Davey Shafik', $value['name']);
        $this->assertSame(40, $value['age']);
        $this->assertSame('davey@php.net', $value['email']);
    }

    public function testItCreatesNewInstanceUsingWith()
    {
        $value = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 39,
            'email' => 'davey@php.net',
        ]);

        $value2 = $value->with(age: 40);

        $this->assertNotSame($value, $value2);
        $this->assertSame('Davey Shafik', $value2->name);
        $this->assertSame(40, $value2->age);
        $this->assertSame('davey@php.net', $value2->email);
    }

    public function testItIgnoresHiddenProperties()
    {
        $value = HiddenPropertiesBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);

        $this->assertSame(['name' => 'Davey Shafik', 'age' => 40], $value->toArray());
    }

    public function testItMapsNames()
    {
        $value = MappedNameClassBag::from([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->nameGoesHere);
        $this->assertSame(40, $value->ageGoesHere);
        $this->assertSame('davey@php.net', $value->emailGoesHere);

        $this->assertSame([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ], $value->toArray());
    }

    public function testItMapsInputNames()
    {
        $value = MappedInputNameClassBag::from([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->nameGoesHere);
        $this->assertSame(40, $value->ageGoesHere);
        $this->assertSame('davey@php.net', $value->emailGoesHere);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $value->toArray());
    }

    public function testItMapsOutputNames()
    {
        $value = MappedOutputNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->nameGoesHere);
        $this->assertSame(40, $value->ageGoesHere);
        $this->assertSame('davey@php.net', $value->emailGoesHere);

        $this->assertSame([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ], $value->toArray());
    }

    public function testItAllowsOriginalNames()
    {
        $value = MappedNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->nameGoesHere);
        $this->assertSame(40, $value->ageGoesHere);
        $this->assertSame('davey@php.net', $value->emailGoesHere);
    }

    public function testItIgnoresHiddenPropertiesInJson()
    {
        $value = HiddenJsonPropertiesBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
            'passwordGoesHere' => 'hunter2',
        ]);

        $this->assertSame('{"name_goes_here":"Davey Shafik","age_goes_here":40}', json_encode($value));
        $this->assertSame('{"name_goes_here":"Davey Shafik","age_goes_here":40}', $value->toJson());
    }

    public function testItErrorsOnAdditionalProperties()
    {
        $this->expectException(AdditionalPropertiesException::class);
        $this->expectExceptionMessage('Additional properties found: foo, baz');

        TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
            'foo' => 'bar',
            'baz' => 'bat',
        ]);
    }

    public function testItErrorsOnMissingProperties()
    {
        $this->expectException(MissingPropertiesException::class);
        $this->expectExceptionMessage('Missing required properties: age, email');

        TestBag::from([
            'name' => 'Davey Shafik',
        ]);
    }

    public function testItCastsInputAndOutput()
    {
        $value = CastsDateBag::from(['date' => '2024-04-12 12:34:56']);

        $this->assertSame('2024-04-12', $value->date->format('Y-m-d'));
        $this->assertSame('2024-04-12', $value->toArray()['date']);
    }

    public function testItCastsInput()
    {
        $value = CastsDateInputBag::from(['date' => '2024-04-12 12:34:56']);

        $this->assertSame('2024-04-12', $value->date->format('Y-m-d'));
        $this->assertInstanceOf(CarbonImmutable::class, $value->toArray()['date']);
    }

    public function testItCastsOutput()
    {
        $value = CastsDateOutputBag::from(['date' => new CarbonImmutable('2024-04-12 12:34:56')]);

        $this->assertSame('2024-04-12', $value->date->format('Y-m-d'));
        $this->assertSame('2024-04-12', $value->toArray()['date']);
    }

    public function testItSupportsVariadicProperties()
    {
        $value = VariadicBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'foo' => 'bar',
            'baz' => 'bat',
            'bing' => 123,
        ]);

        $this->assertSame('Davey Shafik', $value->name);
        $this->assertSame(40, $value->age);
        $this->assertSame(['foo' => 'bar', 'baz' => 'bat', 'bing' => 123], $value->extra);
    }

    public function testItValidates()
    {
        $this->assertTrue(ValidateUsingRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])));
    }

    public function testItFailsValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

        try {
            ValidateUsingRulesMethodBag::validate(collect(['name' => 1234]));
        } catch (ValidationException $e) {
            $this->assertEquals(
                [
                    'name' => [
                        'The name field must be a string.',
                    ],
                    'age' => [
                        'The age field is required.',
                    ],
                ],
                $e->errors()
            );

            throw $e;
        }
    }

    public function testItValidatesUsingRulesMethod()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The age field must be an integer.');

        ValidateUsingRulesMethodBag::from(['name' => 'Davey Shafik', 'age' => 'test string']);
    }

    public function testItValidatesUsingAttributes()
    {
        $this->assertTrue(ValidateUsingAttributesBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])));
    }

    public function testItValidatesUsingBoth()
    {
        $this->assertTrue(ValidateUsingAttributesAndRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])));
    }

    public function testItCreatesCustomCollections()
    {
        $data = [
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ];

        $collection = BagWithCollection::collect($data);

        $this->assertInstanceOf(BagWithCollectionCollection::class, $collection);
        $this->assertCount(2, $collection);
        $collection->each(function (BagWithCollection $bag, $index) use ($data) {
            $this->assertSame($data[$index]['name'], $bag->name);
            $this->assertSame($data[$index]['age'], $bag->age);
        });
    }
}
