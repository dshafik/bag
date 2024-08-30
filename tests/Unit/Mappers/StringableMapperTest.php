<?php

declare(strict_types=1);
use Bag\Mappers\Stringable;

test('it maps using single transform', function () {
    $mapper = new Stringable('upper');

    expect($mapper('some_words_here'))->toBe('SOME_WORDS_HERE');
});

test('it maps using multiple transforms', function () {
    $mapper = new Stringable('upper', 'replace:_,-');

    expect($mapper('some_words_here'))->toBe('SOME-WORDS-HERE');
});
