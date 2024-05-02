<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Concerns\WithProperties;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\NoConstructorBag;
use Tests\Fixtures\NoPropertiesBag;
use Tests\Fixtures\TestBag;

#[CoversClass(WithProperties::class)]
class WithPropertiesTest extends TestCase
{
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

    public function testItHandlesProperties()
    {
        $value = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $this->assertSame('Davey Shafik', $value->name);
        $this->assertSame(40, $value->age);
        $this->assertSame('davey@php.net', $value->email);
    }
}
