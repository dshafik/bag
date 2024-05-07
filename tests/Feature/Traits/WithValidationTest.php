<?php

declare(strict_types=1);

namespace Tests\Feature\Traits;

use Bag\Concerns\WithValidation;
use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Internal\Cache;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\ComputedPropertyBag;
use Tests\Fixtures\Values\ComputedPropertyMissingBag;
use Tests\Fixtures\Values\ValidateMappedNameClassBag;
use Tests\Fixtures\Values\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\Fixtures\Values\ValidateUsingRulesMethodBag;
use Tests\TestCase;

#[CoversClass(WithValidation::class)]
class WithValidationTest extends TestCase
{
    public function testItValidates()
    {
        $this->assertTrue(ValidateUsingRulesMethodBag::validate(collect(['name' => 'Davey Shafik', 'age' => 40])));
    }

    public function testItFailsValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

        try {
            ValidateUsingRulesMethodBag::validate(collect(['name' => 1234, 'age' => 'string']));
        } catch (ValidationException $e) {
            $this->assertEquals(
                [
                    'name' => [
                        'The name field must be a string.',
                    ],
                    'age' => [
                        'The age field must be an integer.',
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

    public function testItValidatesMappedNames()
    {
        $this->assertTrue(ValidateMappedNameClassBag::validate(collect(['nameGoesHere' => 'Davey Shafik', 'ageGoesHere' => 40])));
    }

    public function testItErrorsWithoutInitializedComputedProperty()
    {
        $this->expectException(ComputedPropertyUninitializedException::class);
        $this->expectExceptionMessage('Property Tests\Fixtures\Values\ComputedPropertyMissingBag->dob must be computed');

        ComputedPropertyMissingBag::from(['name' => 'Davey Shafik', 'age' => 40]);
    }

    public function testItValidatesComputedProperties()
    {
        Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

        $bag = ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
        $this->assertSame('1984-05-04', $bag->dob->format('Y-m-d'));
    }

    public function testItUsesCacheForComputedProperties()
    {
        Cache::fake()->shouldReceive('store')->atLeast()->twice()->passthru();

        Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

        ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
        ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
    }
}
