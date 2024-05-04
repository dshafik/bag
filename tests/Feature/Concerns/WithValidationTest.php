<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Concerns\WithValidation;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
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

    public function testItValidatesMappedNames()
    {
        $this->assertTrue(ValidateMappedNameClassBag::validate(collect(['nameGoesHere' => 'Davey Shafik', 'ageGoesHere' => 40])));
    }
}
