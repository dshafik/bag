<?php

declare(strict_types=1);
use Bag\Internal\Cache;
use Mockery\MockInterface;

test('it creates mock', function () {
    expect(Cache::fake())->toBeInstanceOf(MockInterface::class);
});

test('it creates spy', function () {
    expect(Cache::spy())->toBeInstanceOf(MockInterface::class);
});

test('it caches objects', function () {
    $object = new \stdClass();

    $calls = 0;

    $result = Cache::remember(__METHOD__, $object, function () use (&$calls) {
        $calls++;

        return 'test value';
    });

    expect($result)->toBe('test value')
        ->and($calls)->toBe(1);

    $result = Cache::remember(__METHOD__, $object, function () use (&$calls) {
        $calls++;

        return 'test value';
    });

    expect($result)->toBe('test value')
        ->and($calls)->toBe(1);
});

test('it caches scalars', function () {
    $calls = 0;

    $result = Cache::remember(__METHOD__, 'key', function () use (&$calls) {
        $calls++;

        return 'test value';
    });

    expect($result)->toBe('test value')
        ->and($calls)->toBe(1);

    $result = Cache::remember(__METHOD__, 'key', function () use (&$calls) {
        $calls++;

        return 'test value';
    });

    expect($result)->toBe('test value')
        ->and($calls)->toBe(1);
});

beforeEach(fn () => Cache::reset());
