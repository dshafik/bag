<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Attributes\Hidden;
use Bag\Cache;
use Bag\Concerns\WithHiddenProperties;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\HiddenPropertiesBag;
use Tests\TestCase;

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

    public function testItUsesCache()
    {
        Cache::fake()->shouldReceive('store')->atLeast()->twice()->passthru();

        $value = HiddenPropertiesBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);

        $this->assertSame(['name' => 'Davey Shafik', 'age' => 40], $value->toArray());
        $this->assertSame(['name' => 'Davey Shafik', 'age' => 40], $value->toArray());
    }
}
