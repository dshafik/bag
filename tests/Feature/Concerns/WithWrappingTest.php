<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Attributes\Wrap;
use Bag\Attributes\WrapJson;
use Bag\Collection;
use Bag\Concerns\WithWrapping;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Collections\WrappedCollection;
use Tests\Fixtures\Values\BagWithFactory;
use Tests\Fixtures\Values\WrappedBag;
use Tests\Fixtures\Values\WrappedBothBag;
use Tests\Fixtures\Values\WrappedJsonBag;
use Tests\TestCase;

#[CoversClass(WithWrapping::class)]
#[CoversClass(Wrap::class)]
#[CoversClass(WrapJson::class)]
#[CoversClass(Collection::class)]
class WithWrappingTest extends TestCase
{
    public function testItDoesNotWrap()
    {
        $bag = BagWithFactory::factory()->make();

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $bag->toArray());

        $this->assertSame('{"name":"Davey Shafik","age":40}', $bag->toJson());
    }

    public function testItWraps()
    {
        $bag = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ],
        ], $bag->toArray());

        $this->assertSame('{"wrapper":{"name":"Davey Shafik","age":40}}', $bag->toJson());
    }

    public function testItWrapsCollections()
    {
        $collection = WrappedCollection::make(BagWithFactory::factory()->count(2)->make());

        $this->assertSame([
            'collection_wrapper' => [
                [
                    'name' => 'Davey Shafik',
                    'age' => 40,
                ],
                [
                    'name' => 'Davey Shafik',
                    'age' => 40,
                ],
            ],
        ], $collection->toArray());

        $this->assertSame('{"collection_wrapper":[{"name":"Davey Shafik","age":40},{"name":"Davey Shafik","age":40}]}', $collection->toJson());
    }

    public function testItWrapsCollectionsAndNestedValues()
    {
        $collection = WrappedCollection::make([
            WrappedBag::from([
                'name' => 'Davey Shafik',
                'age' => 40,
            ]),
            WrappedBag::from([
                'name' => 'Davey Shafik',
                'age' => 40,
            ]),
        ]);

        $this->assertSame([
            'collection_wrapper' => [
                [
                    'wrapper' => [
                        'name' => 'Davey Shafik',
                        'age' => 40,
                    ],
                ],
                [
                    'wrapper' => [
                        'name' => 'Davey Shafik',
                        'age' => 40,
                    ],
                ],
            ],
        ], $collection->toArray());

        $this->assertSame('{"collection_wrapper":[{"wrapper":{"name":"Davey Shafik","age":40}},{"wrapper":{"name":"Davey Shafik","age":40}}]}', $collection->toJson());
    }

    public function testItWrapsJson()
    {
        $bag = WrappedJsonBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $this->assertSame('{"wrapper":{"name":"Davey Shafik","age":40}}', $bag->toJson());

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $bag->toArray());
    }

    public function testItWrapsBothSeparately()
    {
        $bag = WrappedBothBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $this->assertSame('{"json_wrapper":{"name":"Davey Shafik","age":40}}', $bag->toJson());

        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ],
        ], $bag->toArray());
    }
}
