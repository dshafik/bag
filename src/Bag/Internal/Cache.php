<?php

declare(strict_types=1);

namespace Bag\Internal;

use Mockery;
use Mockery\MockInterface;
use WeakMap;

/**
 * @internal This class is not meant to be used or overwritten outside of the library.
 */
class Cache
{
    /**
     */
    public static self|MockInterface|null $instance = null;

    /**
     * @var array<WeakMap<object,mixed>>
     */
    public static array $cacheWeakMap;

    /**
     * @var array <array-key, array<array-key, mixed>>
     */
    public static array $cacheArray = [];

    public static function fake(): MockInterface
    {
        self::$instance = Mockery::mock(static::class);

        return self::$instance;
    }

    public static function spy(): MockInterface
    {
        self::$instance = Mockery::spy(static::class);

        return self::$instance;
    }

    public static function reset(): void
    {
        self::clear();
        self::$instance = null;
    }

    protected static function getInstance(): self|MockInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @template T
     * @param callable():T $callback
     * @return T
     */
    public static function remember(string $store, object|string $key, callable $callback): mixed
    {
        // @phpstan-ignore method.notFound
        return self::getInstance()->store($store, $key, $callback);
    }

    /**
     * @template T
     * @param callable():T $callback
     * @return T
     */
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

    protected static function clear(): void
    {
        self::$cacheArray = self::$cacheWeakMap = [];
    }

    public function __destruct()
    {
        self::clear();
    }
}
