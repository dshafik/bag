<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Bag\Property\CastInput;
use Bag\Property\CastOutput;
use Bag\Property\ValidatorCollection;
use Bag\Property\Value;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use Tests\Fixtures\Values\ValidateMappedNameClassBag;
use Tests\TestCase;

#[CoversClass(Value::class)]
class ValueTest extends TestCase
{
    public function testItCreatesValueFromProperty()
    {
        $class = new ReflectionClass(ValidateMappedNameClassBag::class);
        $property = $class->getProperty('nameGoesHere');

        $value = Value::create($class, $property);

        $this->assertInstanceOf(Value::class, $value);

        $this->assertInstanceOf(ReflectionClass::class, $value->bag);
        $this->assertSame(ValidateMappedNameClassBag::class, $value->bag->name);

        $this->assertInstanceOf(ReflectionProperty::class, $value->property);
        $this->assertSame('nameGoesHere', $value->property->name);

        $this->assertInstanceOf(ReflectionNamedType::class, $value->type);
        $this->assertSame('string', $value->type->getName());

        $this->assertSame('nameGoesHere', $value->name);

        $this->assertTrue($value->required);

        $this->assertSame('name_goes_here', $value->maps->inputName);
        $this->assertSame('name_goes_here', $value->maps->outputName);

        $this->assertInstanceOf(CastInput::class, $value->inputCast);
        $this->assertInstanceOf(CastOutput::class, $value->outputCast);

        $this->assertInstanceOf(ValidatorCollection::class, $value->validators);
        $this->assertSame(['string', 'required'], $value->validators->all());

        $this->assertFalse($value->variadic);
    }

    public function testItCreatesValueFromParameter()
    {
        $class = new ReflectionClass(ValidateMappedNameClassBag::class);
        $property = $class->getConstructor()->getParameters()[0];

        $value = Value::create($class, $property);

        $this->assertInstanceOf(Value::class, $value);

        $this->assertInstanceOf(ReflectionClass::class, $value->bag);
        $this->assertSame(ValidateMappedNameClassBag::class, $value->bag->name);

        $this->assertInstanceOf(ReflectionParameter::class, $value->property);
        $this->assertSame('nameGoesHere', $value->property->name);

        $this->assertInstanceOf(ReflectionNamedType::class, $value->type);
        $this->assertSame('string', $value->type->getName());

        $this->assertSame('nameGoesHere', $value->name);

        $this->assertTrue($value->required);

        $this->assertSame('name_goes_here', $value->maps->inputName);
        $this->assertSame('name_goes_here', $value->maps->outputName);

        $this->assertInstanceOf(CastInput::class, $value->inputCast);
        $this->assertInstanceOf(CastOutput::class, $value->outputCast);

        $this->assertInstanceOf(ValidatorCollection::class, $value->validators);
        $this->assertSame(['string', 'required'], $value->validators->all());

        $this->assertFalse($value->variadic);
    }
}
