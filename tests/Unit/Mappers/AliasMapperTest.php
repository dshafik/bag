<?php

declare(strict_types=1);
use Bag\Mappers\Alias;

test('it maps using single transform', function () {
    $mapper = new Alias('different_words_go_here');

    expect($mapper('some_words_here'))->toBe('different_words_go_here');
});
