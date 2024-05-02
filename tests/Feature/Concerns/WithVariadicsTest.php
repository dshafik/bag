<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Concerns\WithVariadics;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\TypedVariadicBag;
use Tests\Fixtures\VariadicBag;

#[CoversClass(WithVariadics::class)]
class WithVariadicsTest extends TestCase
{
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
        $this->assertSame(['foo' => 'bar', 'baz' => 'bat', 'bing' => 123], $value->values);
    }

    public function testItSupportsTypedVariadicProperties()
    {
        $value = TypedVariadicBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'foo' => 1,
            'baz' => 0,
            'bing' => null,
            'qux' => 'quux',
        ]);

        $this->assertSame('Davey Shafik', $value->name);
        $this->assertSame(40, $value->age);
        $this->assertSame(['foo' => true, 'baz' => false, 'bing' => false, 'qux' => true], $value->values);
    }
}
