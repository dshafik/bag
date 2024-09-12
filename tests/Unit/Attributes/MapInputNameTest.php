<?php

declare(strict_types=1);
use Bag\Attributes\MapInputName;
use Bag\Mappers\Stringable;

covers(MapInputName::class);

test('it instantiates', function () {
    $map = new MapInputName(Stringable::class, 'foo', 'bar', 'baz');

    expect($map->input)->toBe(Stringable::class)
        ->and($map->inputParams)->toBe(['foo', 'bar', 'baz'])
        ->and($map->output)->toBeNull()
        ->and($map->outputParams)->toBe([]);
});
