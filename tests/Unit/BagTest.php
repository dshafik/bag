<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bag\Bag;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(Bag::class)]
class BagTest extends TestCase
{
    use WithFaker;

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

    public function testItCreatesNewInstanceUsingWithNamedArgs()
    {
        $value = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 39,
            'email' => 'davey@php.net',
        ]);

        $value2 = $value->with(age: 40, email: 'test@example.org');

        $this->assertNotSame($value, $value2);
        $this->assertSame('Davey Shafik', $value2->name);
        $this->assertSame(40, $value2->age);
        $this->assertSame('test@example.org', $value2->email);
    }

    public function testItCreatesNewInstanceUsingWithArray()
    {
        $value = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 39,
            'email' => 'davey@php.net',
        ]);

        $value2 = $value->with(['age' => 40, 'email' => 'test@example.org']);

        $this->assertNotSame($value, $value2);
        $this->assertSame('Davey Shafik', $value2->name);
        $this->assertSame(40, $value2->age);
        $this->assertSame('test@example.org', $value2->email);
    }
}
