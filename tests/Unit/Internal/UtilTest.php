<?php

declare(strict_types=1);

namespace Tests\Unit\Internal;

use Bag\Exceptions\InvalidPropertyType;
use Bag\Internal\Util;
use Bag\Mappers\CamelCase;
use Bag\Mappers\Stringable;
use Illuminate\Pipeline\Pipeline;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

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

    public function testItCreatesAPipeline()
    {
        $this->assertInstanceOf(Pipeline::class, Util::getPipeline());
    }
}
