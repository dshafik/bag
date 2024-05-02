<?php

declare(strict_types=1);

namespace Tests\Unit\Casts;

use Bag\Casts\MagicCast;
use Bag\Collection;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use DateTimeImmutable;
use Illuminate\Support\Carbon as LaravelCarbon;
use Illuminate\Support\Collection as LaravelCollection;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Enums\TestBackedEnum;
use Tests\Fixtures\Enums\TestUnitEnum;
use Tests\Fixtures\TestBag;

#[CoversClass(MagicCast::class)]
class MagicCastTest extends TestCase
{
    public function testItCastsInt()
    {
        $cast = new MagicCast();

        $int = $cast->set('int', 'test', collect(['test' => 1]));
        $this->assertIsInt($int);
        $this->assertSame(1, $int);

        $int = $cast->set('int', 'test', collect(['test' => 1.7]));
        $this->assertIsInt($int);
        $this->assertSame(1, $int);
    }

    public function testItCastsFloat()
    {
        $cast = new MagicCast();

        $float = $cast->set('float', 'test', collect(['test' => 1]));
        $this->assertIsFloat($float);
        $this->assertSame(1.0, $float);

        $float = $cast->set('float', 'test', collect(['test' => 1.7]));
        $this->assertIsFloat($float);
        $this->assertSame(1.7, $float);
    }

    public function testItCastsBoolean()
    {
        $cast = new MagicCast();

        $boolean = $cast->set('bool', 'test', collect(['test' => 0]));
        $this->assertFalse($boolean);

        $boolean = $cast->set('bool', 'test', collect(['test' => 1]));
        $this->assertTrue($boolean);
    }

    public function testItCastsString()
    {
        $cast = new MagicCast();

        $string = $cast->set('string', 'test', collect(['test' => 'testing']));
        $this->assertIsString($string);
        $this->assertSame('testing', $string);

        $string = $cast->set('string', 'test', collect(['test' => 1234]));
        $this->assertIsString($string);
        $this->assertSame('1234', $string);
    }

    public function testItCastsDateTimes()
    {
        $cast = new MagicCast();

        $date = $cast->set(DateTime::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
        $this->assertInstanceOf(DateTime::class, $date);
        $this->assertSame('2024-04-30 11:43:41', $date->format('Y-m-d H:i:s'));

        $date = $cast->set(DateTimeImmutable::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
        $this->assertInstanceOf(DateTimeImmutable::class, $date);
        $this->assertSame('2024-04-30 11:43:41', $date->format('Y-m-d H:i:s'));

        $date = $cast->set(Carbon::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
        $this->assertInstanceOf(Carbon::class, $date);
        $this->assertSame('2024-04-30 11:43:41', $date->format('Y-m-d H:i:s'));

        $date = $cast->set(CarbonImmutable::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
        $this->assertInstanceOf(CarbonImmutable::class, $date);
        $this->assertSame('2024-04-30 11:43:41', $date->format('Y-m-d H:i:s'));

        $date = $cast->set(LaravelCarbon::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
        $this->assertInstanceOf(LaravelCarbon::class, $date);
        $this->assertSame('2024-04-30 11:43:41', $date->format('Y-m-d H:i:s'));

        $class = new class () extends CarbonImmutable { };
        $date = $cast->set($class::class, 'test', collect(['test' => '2024-04-30 11:43:41']));
        $this->assertInstanceOf($class::class, $date);
        $this->assertSame('2024-04-30 11:43:41', $date->format('Y-m-d H:i:s'));
    }

    public function testItCastsBags()
    {
        $cast = new MagicCast();

        $bag = $cast->set(TestBag::class, 'test', collect([
            'test' => [
                'name' => 'Davey Shafik',
                'age' => '40',
                'email' => 'davey@php.net'
            ]
        ]));

        $this->assertInstanceOf(TestBag::class, $bag);
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItCastsCollections()
    {
        $cast = new MagicCast();

        $collection = $cast->set(LaravelCollection::class, 'test', collect([
            'test' => [
                'name' => 'Davey Shafik',
                'age' => '40',
                'email' => 'davey@php.net'
            ]
        ]));
        $this->assertInstanceOf(LaravelCollection::class, $collection);
        $this->assertSame('Davey Shafik', $collection['name']);
        $this->assertSame('40', $collection['age']);
        $this->assertSame('davey@php.net', $collection['email']);

        $collection = $cast->set(Collection::class, 'test', collect([
            'test' => [
                'name' => 'Davey Shafik',
                'age' => '40',
                'email' => 'davey@php.net'
            ]
        ]));
        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertSame('Davey Shafik', $collection['name']);
        $this->assertSame('40', $collection['age']);
        $this->assertSame('davey@php.net', $collection['email']);
    }


    public function testItCastsUnitEnum()
    {
        $cast = new MagicCast();

        $enum = $cast->set(TestUnitEnum::class, 'test', collect(['test' => 'TEST_VALUE']));

        $this->assertSame(TestUnitEnum::TEST_VALUE, $enum);
    }


    public function testItCastsBackedEnum()
    {
        $cast = new MagicCast();

        $enum = $cast->set(TestBackedEnum::class, 'test', collect(['test' => 'test']));

        $this->assertSame(TestBackedEnum::TEST_VALUE, $enum);
    }
}
