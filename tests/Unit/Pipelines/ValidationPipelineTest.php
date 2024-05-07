<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines;

use Bag\Pipelines\ValidationPipeline;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\ValidateUsingAttributesAndRulesMethodBag;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\Fixtures\Values\ValidateUsingRulesMethodBag;
use Tests\TestCase;

#[CoversClass(ValidationPipeline::class)]
class ValidationPipelineTest extends TestCase
{
    public function testItValidatesWithoutRules()
    {
        $input = new BagInput(TestBag::class, [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $validation = ValidationPipeline::process($input);

        $this->assertTrue($validation);
    }

    public function testItValidatesUsingRules()
    {
        $input = new BagInput(ValidateUsingRulesMethodBag::class, [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $validation = ValidationPipeline::process($input);

        $this->assertTrue($validation);
    }

    public function testItFailsValidationUsingRules()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

        $input = new BagInput(ValidateUsingRulesMethodBag::class, [
            'name' => 1234,
            'age' => 'testing',
        ]);

        try {
            ValidationPipeline::process($input);
        } catch (ValidationException $e) {
            $this->assertSame([
                'name' => ['The name field must be a string.'],
                'age' => ['The age field must be an integer.']
            ], $e->errors());

            throw $e;
        }
    }

    public function testItValidatesUsingAttributes()
    {
        $input = new BagInput(ValidateUsingAttributesBag::class, [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $validation = ValidationPipeline::process($input);

        $this->assertTrue($validation);
    }

    public function testItFailsValidationUsingAttributes()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field must be a string. (and 1 more error)');

        $input = new BagInput(ValidateUsingAttributesBag::class, [
            'name' => 1234,
            'age' => 'testing',
        ]);

        try {
            ValidationPipeline::process($input);
        } catch (ValidationException $e) {
            $this->assertSame([
                'name' => ['The name field must be a string.'],
                'age' => ['The age field must be an integer.']
            ], $e->errors());

            throw $e;
        }
    }

    public function testItValidatesUsingBoth()
    {
        $input = new BagInput(ValidateUsingAttributesAndRulesMethodBag::class, [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $validation = ValidationPipeline::process($input);

        $this->assertTrue($validation);
    }

    public function testItFailsValidationUsingBoth()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field must not be greater than 100 characters. (and 1 more error)');

        $input = new BagInput(ValidateUsingAttributesAndRulesMethodBag::class, [
            'name' => \str_repeat('Davey Shafik', 40),
            'age' => 200,
        ]);

        try {
            ValidationPipeline::process($input);
        } catch (ValidationException $e) {
            $this->assertSame([
                'name' => ['The name field must not be greater than 100 characters.'],
                'age' => ['The age field must not be greater than 100.']
            ], $e->errors());

            throw $e;
        }
    }
}
