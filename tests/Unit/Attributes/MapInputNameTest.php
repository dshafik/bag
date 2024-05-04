<?php

declare(strict_types=1);

namespace Tests\Unit\Attributes;

use Bag\Attributes\MapInputName;
use Bag\Mappers\Stringable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(MapInputName::class)]
class MapInputNameTest extends TestCase
{
    public function testItInstantiates()
    {
        $map = new MapInputName(Stringable::class, 'foo', 'bar', 'baz');

        $this->assertSame(Stringable::class, $map->input);
        $this->assertSame(['foo', 'bar', 'baz'], $map->inputParams);
        $this->assertNull($map->output);
        $this->assertSame([], $map->outputParams);
    }
}
