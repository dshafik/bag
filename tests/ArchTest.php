<?php

declare(strict_types=1);

arch('it does not call dd()')->expect('dd')->not->toBeUsed();
arch('it does not call ddd()')->expect('ddd')->not->toBeUsed();
arch('it does not call dump()')->expect('dump')->not->toBeUsed();
arch('it does not call xdebug_break()')->expect('xdebug_break')->not->toBeUsed();

arch('it meets PHP preset')
    ->preset()
    ->php()
    ->skip(fn () => !version_compare(Pest\version(), '3.0.0', '>='), 'Requires Pest 3+');
