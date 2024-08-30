<?php

declare(strict_types=1);
use Bag\Mappers\SnakeCase;

test('it transforms to snake case', function () {
    $mapper = new SnakeCase();

    expect($mapper('someWordsHere'))->toBe('some_words_here');
});

test('it leaves snake case alone', function () {
    $mapper = new SnakeCase();

    expect($mapper('some_words_here'))->toBe('some_words_here');
});
