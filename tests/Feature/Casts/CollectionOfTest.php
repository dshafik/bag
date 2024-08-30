<?php

declare(strict_types=1);
use Tests\Fixtures\Values\BagWithLaravelCollectionOf;
use Tests\Fixtures\Values\TestBag;

test('it creates laravel collection of bags', function () {
    $bag = BagWithLaravelCollectionOf::from([
        'bags' => [
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ],
            [
                'name' => 'David Shafik',
                'age' => 40,
                'email' => 'david@example.org',
            ],
        ],
    ]);

    expect($bag->bags)->toContainOnlyInstancesOf(TestBag::class);
});
