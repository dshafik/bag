<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\WrapCollection;
use Bag\Pipelines\Values\CollectionOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Collections\WrappedCollection;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(WrapCollection::class)]
#[CoversClass(CollectionOutput::class)]
class WrapCollectionTest extends TestCase
{
    public function testItDoesNotWrapCollectionWithNoWrapper()
    {
        $collection = TestBag::collect([[
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]]);

        $output = new CollectionOutput($collection, OutputType::ARRAY);

        $pipe = new WrapCollection();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ]
        ], $output->collection->toArray());
    }

    public function testItDoesNotWrapCollectionUnwrappedOutput()
    {
        $collection = TestBag::collect([[
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]]);

        $output = new CollectionOutput($collection, OutputType::UNWRAPPED);

        $pipe = new WrapCollection();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net',
            ]
        ], $output->collection->toArray());
    }

    public function testItWrapsCollectionArray()
    {
        $collection = WrappedCollection::make([
            TestBag::from([
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net'
            ])
        ]);

        $output = new CollectionOutput($collection, OutputType::ARRAY);

        $pipe = new WrapCollection();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            'collection_wrapper' => [
                [
                    'name' => 'Davey Shafik',
                    'age' => 40,
                    'email' => 'davey@php.net',
                ]
            ]
        ], $output->collection->toArray());
    }

    public function testItWrapsCollectionJson()
    {
        $collection = WrappedCollection::make([
            TestBag::from([
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net'
            ])
        ]);

        $output = new CollectionOutput($collection, OutputType::JSON);

        $pipe = new WrapCollection();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            'collection_json_wrapper' => [
                [
                    'name' => 'Davey Shafik',
                    'age' => 40,
                    'email' => 'davey@php.net',
                ]
            ]
        ], $output->collection->toArray());
    }
}
