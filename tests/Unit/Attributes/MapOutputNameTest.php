<?php

declare(strict_types=1);
use Bag\Attributes\MapOutputName;
use Bag\Mappers\Stringable;

test('it instantiates', function () {
    $map = new MapOutputName(Stringable::class, 'foo', 'bar', 'baz');

    expect($map->output)->toBe(Stringable::class)
        ->and($map->outputParams)->toBe(['foo', 'bar', 'baz'])
        ->and($map->input)->toBeNull()
        ->and($map->inputParams)->toBe([]);
});
