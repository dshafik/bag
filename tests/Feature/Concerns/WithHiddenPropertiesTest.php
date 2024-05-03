<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Attributes\Hidden;
use Bag\Concerns\WithHiddenProperties;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\HiddenPropertiesBag;

#[CoversClass(WithHiddenProperties::class)]
#[CoversClass(Hidden::class)]
class WithHiddenPropertiesTest extends TestCase
{
    public function testItIgnoresHiddenProperties()
    {
        $value = HiddenPropertiesBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);

        $this->assertSame(['name' => 'Davey Shafik', 'age' => 40], $value->toArray());
    }
}
