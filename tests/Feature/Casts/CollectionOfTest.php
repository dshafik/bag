<?php

declare(strict_types=1);

use Bag\Casts\CollectionOf;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\BagWithCustomCollectionOf;
use Tests\Fixtures\Values\BagWithLaravelCollectionOf;
use Tests\Fixtures\Values\TestBag;

covers(CollectionOf::class);

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

test('it creates custom collection of bags', function () {
    $bag = BagWithCustomCollectionOf::from([
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

    expect($bag->bags)->toBeInstanceOf(BagWithCollectionCollection::class)->toContainOnlyInstancesOf(TestBag::class);
});
