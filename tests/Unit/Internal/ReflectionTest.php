<?php

declare(strict_types=1);

namespace Tests\Unit\Internal;

use Bag\Internal\Reflection;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use ReflectionMethod;
use Tests\TestCase;

#[CoversClass(Reflection::class)]
class ReflectionTest extends TestCase
{
    public function testItGetsClass()
    {
        $class = Reflection::getClass(static::class);
        $this->assertSame(static::class, $class->getName());
    }

    public function testItGetsClassWithReflectionClass()
    {
        $class = Reflection::getClass(new ReflectionClass(static::class));
        $this->assertSame(static::class, $class->getName());
    }

    public function testItGetsConstructor()
    {
        $constructor = Reflection::getConstructor(static::class);
        $this->assertInstanceOf(ReflectionMethod::class, $constructor);
        $this->assertSame('__construct', $constructor->getName());
    }

    public function testItGetsConstructorOnExistingReflectionClass()
    {
        $class = new ReflectionClass(static::class);
        $constructor = Reflection::getConstructor($class);
        $this->assertInstanceOf(ReflectionMethod::class, $constructor);
        $this->assertSame('__construct', $constructor->getName());
    }

    public function testItReturnsNullWhenNoConstructor()
    {
        $this->assertNull(Reflection::getConstructor(Reflection::class));
    }

    public function testItGetsProperties()
    {
        $properties = Reflection::getProperties(static::class);
        $this->assertIsArray($properties);
    }

    public function testItReturnsEmptyWhenGettingPropertiesOnNull()
    {
        $this->assertEmpty(Reflection::getProperties(null));
    }

    public function testItGetsParameters()
    {
        $method = new ReflectionMethod(static::class, 'setUp');
        $parameters = Reflection::getParameters($method);
        $this->assertIsArray($parameters);
    }

    public function testItReturnsEmptyWhenGettingParametersOnNull()
    {
        $this->assertEmpty(Reflection::getParameters(null));
    }

    public function testItGetsAttributes()
    {
        $class = new ReflectionClass(static::class);
        $attributes = Reflection::getAttributes($class, CoversClass::class);
        $this->assertIsArray($attributes);
    }

    public function testItReturnsEmptyWhenGettingAttributesOnNull()
    {
        $this->assertEmpty(Reflection::getAttributes(null, CoversClass::class));
    }

    public function testItGetsAttribute()
    {
        $class = new ReflectionClass(static::class);
        $attribute = Reflection::getAttribute($class, CoversClass::class);
        $this->assertInstanceOf(\ReflectionAttribute::class, $attribute);
    }

    public function testItReturnsNullWhenGettingNonExistentAttribute()
    {
        $class = new ReflectionClass(static::class);
        $this->assertNull(Reflection::getAttribute($class, 'NonExistentAttribute'));
    }

    public function testItReturnsNullWhenGettingAttributeOnNull()
    {
        $this->assertNull(Reflection::getAttribute(null, CoversClass::class));
    }

    public function testItGetsAttributeInstance()
    {
        $class = new ReflectionClass(static::class);
        $instance = Reflection::getAttributeInstance($class, CoversClass::class);
        $this->assertIsObject($instance);
    }

    public function testItReturnsNullWhenGettingAttributeInstanceWithNonExistentAttribute()
    {
        $class = new ReflectionClass(static::class);
        $this->assertNull(Reflection::getAttributeInstance($class, 'NonExistentAttribute'));
    }

    public function testItReturnsNullWhenGettingAttributeInstanceOnNull()
    {
        $this->assertNull(Reflection::getAttributeInstance(null, CoversClass::class));
    }

    public function testItGetsAttributeArguments()
    {
        $class = Reflection::getClass(static::class);
        $attribute = Reflection::getAttribute($class, CoversClass::class);
        $arguments = Reflection::getAttributeArguments($attribute);
        $this->assertIsArray($arguments);
    }
}
