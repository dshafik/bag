<?php

declare(strict_types=1);

namespace Tests\Unit\Casts;

use Bag\Casts\DateTime;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\Exceptions\InvalidFormatException;
use DateTimeImmutable;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(DateTime::class)]
class DateTimeTest extends TestCase
{
    public function testItCastsToDatetime()
    {
        $cast = new DateTime();

        $datetime = $cast->set(\DateTime::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

        $this->assertInstanceOf(\DateTime::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItCastsToDatetimeImmutable()
    {
        $cast = new DateTime();

        $datetime = $cast->set(DateTimeImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

        $this->assertInstanceOf(DateTimeImmutable::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItCastsToCarbon()
    {
        $cast = new DateTime();

        $datetime = $cast->set(Carbon::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

        $this->assertInstanceOf(Carbon::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItCastsToCarbonImmutable()
    {
        $cast = new DateTime();

        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

        $this->assertInstanceOf(CarbonImmutable::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItCastsToCustom()
    {
        $customDateTime = new class () extends CarbonImmutable {};
        $cast = new DateTime(dateTimeClass: $customDateTime::class);

        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

        $this->assertInstanceOf($customDateTime::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItDoesNotCastSame()
    {
        $cast = new DateTime();

        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => new CarbonImmutable('2024-04-30 01:58:23')]));

        $this->assertInstanceOf(CarbonImmutable::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItCastsIncorrectDateTimeInterface()
    {
        $cast = new DateTime();

        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => new DateTimeImmutable('2024-04-30 01:58:23')]));

        $this->assertInstanceOf(CarbonImmutable::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItEnforcesStrictMode()
    {
        $cast = new DateTime(strictMode: true);

        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58:23']));

        $this->assertInstanceOf(CarbonImmutable::class, $datetime);
        $this->assertSame('2024-04-30 01:58:23', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItErrorsInStrictMode()
    {
        $this->expectException(InvalidFormatException::class);
        $this->expectExceptionMessage('Not enough data available to satisfy format');

        $cast = new DateTime(strictMode: true);
        $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58']));
    }

    public function testItDoesNotErrorInNonStrictMode()
    {
        $cast = new DateTime(strictMode: false);
        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 01:58']));

        $this->assertInstanceOf(CarbonImmutable::class, $datetime);
        $this->assertSame('2024-04-30 01:58:00', $datetime->format('Y-m-d H:i:s'));
    }

    public function testItParsesCustomFormat()
    {
        $cast = new DateTime(format: 'm/d/y');

        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '4/30/24']));

        $this->assertSame('2024-04-30', $datetime->format('Y-m-d'));
    }

    public function testItFormatsOutputUsingInputFormatByDefault()
    {
        $cast = new DateTime();
        $output = $cast->get('test', \collect(['test' => new CarbonImmutable('2024-04-30 01:58:23')]));

        $this->assertSame('2024-04-30 01:58:23', $output);
    }

    public function testItParsesAndOutputsCustomFormat()
    {
        $cast = new DateTime(format: 'm/d/y H:i:s', outputFormat: 'Y-m-d');

        $datetime = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '4/30/24 01:58:23']));

        $this->assertSame('2024-04-30', $datetime->format('Y-m-d'));
        $this->assertSame('2024-04-30', $cast->get('test', collect(['test' => $datetime])));
    }
}
