<?php

declare(strict_types=1);

beforeEach()->coversNothing();

arch('it does not call dd()')->expect('dd')->not->toBeUsed();
arch('it does not call ddd()')->expect('ddd')->not->toBeUsed();
arch('it does not call dump()')->expect('dump')->not->toBeUsed();
arch('it does not call xdebug_break()')->expect('xdebug_break')->not->toBeUsed();

arch('it meets PHP preset')
    ->skip(fn () => !version_compare(Pest\version(), '3.0.0', '>='), 'Requires Pest 3+')
    ->preset()
    ->php()
    ->ignoring(['var_export', 'debug_backtrace']);
