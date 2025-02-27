<?php

declare(strict_types=1);

use Bag\DebugBar\Collectors\BagCollector;
use Illuminate\Support\Facades\Config;
use Tests\Fixtures\Values\TestBag;

covers(BagCollector::class);

beforeEach(function () {
    BagCollector::init();
});

test('it collects a bag', function () {
    $bag = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $time = microtime(true);

    BagCollector::add($bag);

    $bagCollector = new BagCollector('bags');
    $collected = $bagCollector->collect();

    expect($collected)
        ->toBeArray()
        ->and($collected['count'])
        ->toBe(1)
        ->and($collected['messages'][0]['message'])
        ->toMatch('/Tests\\\Fixtures\\\Values\\\TestBag \{#\d+\n\s+\+name: "Davey Shafik"\n\s+\+age: 40\n\s+\+email: "davey@php\.net"\n\s*\}/')
        ->and($collected['messages'][0]['message_html'])
        ->toBeNull()
        ->and($collected['messages'][0]['is_string'])
        ->toBeFalse()
        ->and($collected['messages'][0]['label'])
        ->toBe('add')
        ->and($collected['messages'][0]['time'])
        ->toBeFloat()
        ->toBeGreaterThan($time)
        ->and($collected['messages'][0]['xdebug_link'])
        ->toBeNull()
    ;
});

test('it collects bags', function () {
    $bag = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net'
    ]);

    $bag2 = TestBag::from([
        'name' => fake()->name(),
        'age' => fake()->numberBetween(18, 65),
        'email' => fake()->email()
    ]);

    $time = microtime(true);

    BagCollector::add($bag);
    BagCollector::add($bag2);

    $bagCollector = new BagCollector('bags');
    $collected = $bagCollector->collect();

    expect($collected)
        ->toBeArray()
        ->and($collected['count'])
        ->toBe(2)
    ;
});

test('it generates xdebug links', function () {
    $bag = TestBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);

    Config::set('debugbar.editor', 'phpstorm');

    BagCollector::add($bag);

    $bagCollector = new BagCollector('bags');
    $collected = $bagCollector->collect();

    expect($collected['messages'][0]['xdebug_link'])
        ->and($collected['messages'][0]['xdebug_link']['url'])
        ->toMatch('/phpstorm:\/\/open\?file=.*%2Ftests%2FUnit%2FDebugBar%2FCollectors%2FBagCollectorTest\.php&line=\d+/')
        ->and($collected['messages'][0]['xdebug_link']['ajax'])
        ->toBeFalse()
        ->and($collected['messages'][0]['xdebug_link']['filename'])
        ->toBe('BagCollectorTest.php')
        ->and($collected['messages'][0]['xdebug_link']['line'])
        ->toBeNumeric();
});
