<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Bag\Attributes\Cast;
use Bag\Casts\DateTime;
use Bag\Property\CastOutput;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use Tests\Fixtures\CastsDateBag;
use Tests\TestCase;

#[CoversClass(CastOutput::class)]
class CastOutputTest extends TestCase
{
    public function testItCreates()
    {
        $param = (new ReflectionClass(CastsDateBag::class))->getConstructor()->getParameters()[0];

        $castOutput = CastOutput::create($param);

        $this->assertInstanceOf(CastOutput::class, $castOutput);
        $this->assertSame(CarbonImmutable::class, $this->prop($castOutput, 'propertyType'));
        $this->assertSame(['format' => 'Y-m-d'], $this->prop($this->prop($castOutput, 'caster'), 'parameters'));
        $this->assertSame(DateTime::class, $this->prop($this->prop($castOutput, 'caster'), 'casterClassname'));
    }

    public function testItCasts()
    {
        $caster = $this->createMock(Cast::class);
        $caster->method('transform')
            ->willReturn('castedValue');

        $castOutput = new CastOutput('string', 'propertyName', $caster);

        $properties = new Collection(['propertyName' => 'propertyValue']);

        $this->assertEquals('castedValue', $castOutput->__invoke($properties));
    }

    public function testItDoesNotCasts()
    {
        $castOutput = new CastOutput('string', 'propertyName', null);

        $properties = new Collection(['propertyName' => 'propertyValue']);

        $this->assertEquals('propertyValue', $castOutput->__invoke($properties));
    }
}
