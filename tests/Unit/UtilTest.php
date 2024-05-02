<?php

declare(strict_types=1);

namespace Tests\Unit;

use Bag\Exceptions\InvalidPropertyType;
use Bag\Mappers\CamelCase;
use Bag\Mappers\Stringable;
use Bag\Util;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Util::class)]
class UtilTest extends TestCase
{
    public function testItGetsPropertyType()
    {
        $type = Util::getPropertyType(new \ReflectionParameter(fn (string $arg) => null, 'arg'));

        $this->assertSame('string', $type->getName());
    }

    public function testItDefaultsToMixedType()
    {
        $type = Util::getPropertyType(new \ReflectionParameter(fn ($arg) => null, 'arg'));

        $this->assertSame('mixed', $type->getName());
    }

    public function testItUsesFirstUnionType()
    {
        $type = Util::getPropertyType(new \ReflectionParameter(fn (int|string|float|bool $arg) => null, 'arg'));

        $this->assertSame('string', $type->getName());
    }

    public function testItErrorsOnIntersectionType()
    {
        $this->expectException(InvalidPropertyType::class);
        $this->expectExceptionMessage('Intersection types are not supported for parameter anArgument');
        Util::getPropertyType(new \ReflectionParameter(fn (CamelCase&Stringable $anArgument) => null, 'anArgument'));
    }
}
