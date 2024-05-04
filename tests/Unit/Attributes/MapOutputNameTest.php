<?php

declare(strict_types=1);

namespace Tests\Unit\Attributes;

use Bag\Attributes\MapOutputName;
use Bag\Mappers\Stringable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(MapOutputName::class)]
class MapOutputNameTest extends TestCase
{
    public function testItInstantiates()
    {
        $map = new MapOutputName(Stringable::class, 'foo', 'bar', 'baz');

        $this->assertSame(Stringable::class, $map->output);
        $this->assertSame(['foo', 'bar', 'baz'], $map->outputParams);
        $this->assertNull($map->input);
        $this->assertSame([], $map->inputParams);
    }
}
