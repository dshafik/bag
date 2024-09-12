<?php

declare(strict_types=1);
use Bag\Mappers\CamelCase;

covers(CamelCase::class);

test('it transforms to camel case', function () {
    $mapper = new CamelCase();

    expect($mapper('some_words_here'))->toBe('someWordsHere');
});

test('it leaves camel case alone', function () {
    $mapper = new CamelCase();

    expect($mapper('someWordsHere'))->toBe('someWordsHere');
});
