<?php

declare(strict_types=1);

namespace Tests\Feature\Casts;

use Bag\Casts\CollectionOf;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\BagWithLaravelCollectionOf;
use Tests\Fixtures\Values\TestBag;

#[CoversClass(CollectionOf::class)]
class CollectionOfTest extends TestCase
{
    public function testItCreatesLaravelCollectionOfBags()
    {
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

        $this->assertContainsOnlyInstancesOf(TestBag::class, $bag->bags);
    }
}
