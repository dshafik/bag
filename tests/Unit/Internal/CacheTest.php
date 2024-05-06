<?php

declare(strict_types=1);

namespace Tests\Unit\Internal;

use Bag\Internal\Cache;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use stdClass;
use Tests\TestCase;

#[CoversClass(Cache::class)]
class CacheTest extends TestCase
{
    public function testItCreatesMock()
    {
        $this->assertInstanceOf(MockInterface::class, Cache::fake());
    }

    public function testItCreatesSpy()
    {
        $this->assertInstanceOf(MockInterface::class, Cache::spy());
    }

    public function testItCachesObjects()
    {
        $object = new stdClass();

        $calls = 0;

        $result = Cache::remember(__METHOD__, $object, function () use (&$calls) {
            $calls++;

            return 'test value';
        });

        $this->assertSame('test value', $result);
        $this->assertSame(1, $calls);

        $result = Cache::remember(__METHOD__, $object, function () use (&$calls) {
            $calls++;

            return 'test value';
        });

        $this->assertSame('test value', $result);
        $this->assertSame(1, $calls);
    }


    public function testItCachesScalars()
    {
        $calls = 0;

        $result = Cache::remember(__METHOD__, 'key', function () use (&$calls) {
            $calls++;

            return 'test value';
        });

        $this->assertSame('test value', $result);
        $this->assertSame(1, $calls);

        $result = Cache::remember(__METHOD__, 'key', function () use (&$calls) {
            $calls++;

            return 'test value';
        });

        $this->assertSame('test value', $result);
        $this->assertSame(1, $calls);
    }
}
