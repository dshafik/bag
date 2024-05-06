<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Bag\Attributes\Cast;
use Bag\Casts\DateTime;
use Bag\Property\CastInput;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use Tests\Fixtures\Values\CastsDateBag;
use Tests\TestCase;

#[CoversClass(CastInput::class)]
class CastInputTest extends TestCase
{
    public function testItCreates()
    {
        $param = (new ReflectionClass(CastsDateBag::class))->getConstructor()->getParameters()[0];

        $castInput = CastInput::create($param);

        $this->assertInstanceOf(CastInput::class, $castInput);
        $this->assertSame(CarbonImmutable::class, $this->prop($castInput, 'propertyType'));
        $this->assertSame('date', $this->prop($castInput, 'name'));
        $this->assertSame(['format' => 'Y-m-d'], $this->prop($this->prop($castInput, 'caster'), 'parameters'));
        $this->assertSame(DateTime::class, $this->prop($this->prop($castInput, 'caster'), 'casterClassname'));
    }

    public function testItCasts()
    {
        $caster = $this->createMock(Cast::class);
        $caster->method('cast')
            ->willReturn('castedValue');

        $castInput = new CastInput('string', 'propertName', $caster);

        $properties = new Collection(['propertyName' => 'propertyValue']);

        $this->assertEquals('castedValue', $castInput->__invoke($properties));
    }
}
