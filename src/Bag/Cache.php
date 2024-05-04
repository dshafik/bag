<?php

declare(strict_types=1);

namespace Bag;

use Mockery;
use WeakMap;

/**
 * @internal This class is not meant to be used or overwritten outside of the library.
 */
class Cache
{
    public static ?self $instance = null;

    /**
     * @var array<WeakMap>
     */
    public static array $cacheWeakMap;

    public static array $cacheArray = [];

    public static function fake()
    {
        self::$instance = Mockery::mock(static::class);

        return self::$instance;
    }

    public static function spy()
    {
        self::$instance = Mockery::spy(static::class);

        return self::$instance;
    }

    public static function reset()
    {
        self::$instance = null;
    }

    protected static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function remember(string $store, object|string $key, callable $callback): mixed
    {
        return self::getInstance()->store($store, $key, $callback);
    }

    public function store(string $store, object|string $key, callable $callback): mixed
    {
        if (is_object($key)) {
            if (!isset(self::$cacheWeakMap[$store])) {
                self::$cacheWeakMap[$store] = new WeakMap();
            }

            if (!isset(self::$cacheWeakMap[$store][$key])) {
                self::$cacheWeakMap[$store][$key] = $callback();
            }

            return self::$cacheWeakMap[$store][$key];
        }

        if (!isset(self::$cacheArray[$store])) {
            self::$cacheArray[$store] = [];
        }

        if (!isset(self::$cacheArray[$store][$key])) {
            self::$cacheArray[$store][$key] = $callback();
        }

        return self::$cacheArray[$store][$key];
    }

    public function __destruct()
    {
        self::$cacheArray = self::$cacheWeakMap = [];
    }
}
