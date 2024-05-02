<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Concerns\WithArrayable;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\TestBag;

#[CoversClass(WithArrayable::class)]
class WithArrayableTest extends TestCase
{
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
}
