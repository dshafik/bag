<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\Validate;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\ValidateMappedNameClassBag;
use Tests\Fixtures\Values\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\Fixtures\Values\ValidateUsingRulesMethodBag;
use Tests\TestCase;

#[CoversClass(Validate::class)]
class ValidateTest extends TestCase
{
    public function testItValidates()
    {
        $input = new BagInput(ValidateUsingRulesMethodBag::class, collect(['name' => 'Davey Shafik', 'age' => 40]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new Validate();
        $input = $pipe($input);

        $this->assertInstanceOf(BagInput::class, $input);
    }

    public function testItValidatesWithNoRules()
    {
        $input = new BagInput(TestBag::class, collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new Validate();
        $input = $pipe($input);

        $this->assertInstanceOf(BagInput::class, $input);
    }

    public function testItFailsValidation()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

        try {
            $input = new BagInput(ValidateUsingRulesMethodBag::class, collect(['name' => 1234]));
            $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
            $input = (new MapInput())($input, fn (BagInput $input) => $input);
            $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

            $pipe = new Validate();
            $pipe($input);
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

        $input = new BagInput(ValidateUsingRulesMethodBag::class, collect(['name' => 'Davey Shafik', 'age' => 'test string']));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new Validate();
        $pipe($input);
    }

    public function testItValidatesUsingAttributes()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The age field must be an integer.');

        $input = new BagInput(ValidateUsingAttributesBag::class, collect(['name' => 'Davey Shafik', 'age' => 'test string']));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new Validate();
        $pipe($input);
    }

    public function testItValidatesUsingBoth()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

        try {
            $input = new BagInput(ValidateUsingAttributesAndRulesMethodBag::class, collect(['name' => 1234, 'age' => 200]));
            $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
            $input = (new MapInput())($input, fn (BagInput $input) => $input);
            $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

            $pipe = new Validate();
            $pipe($input);
        } catch (ValidationException $e) {
            $this->assertEquals(
                [
                    'name' => [
                        'The name field must be a string.',
                    ],
                    'age' => [
                        'The age field must not be greater than 100.',
                    ],
                ],
                $e->errors()
            );

            throw $e;
        }
    }

    public function testItValidatesMappedNames()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name goes here field must be a string. (and 1 more error)');

        try {
            $input = new BagInput(ValidateMappedNameClassBag::class, collect(['nameGoesHere' => 1234]));
            $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
            $input = (new MapInput())($input, fn (BagInput $input) => $input);
            $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

            $pipe = new Validate();
            $pipe($input);
        } catch (ValidationException $e) {
            $this->assertEquals(
                [
                    'nameGoesHere' => [
                        'The name goes here field must be a string.',
                    ],
                    'ageGoesHere' => [
                        'The age goes here field is required.',
                    ],
                ],
                $e->errors()
            );

            throw $e;
        }
    }
}
