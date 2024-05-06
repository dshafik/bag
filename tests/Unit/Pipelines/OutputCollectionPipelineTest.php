<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines;

use Bag\Enums\OutputType;
use Bag\Pipelines\OutputCollectionPipeline;
use Bag\Pipelines\Values\CollectionOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Collections\WrappedCollection;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\WrappedBag;
use Tests\Fixtures\Values\WrappedJsonBag;
use Tests\TestCase;

#[CoversClass(OutputCollectionPipeline::class)]
class OutputCollectionPipelineTest extends TestCase
{
    public function testItGetArray()
    {
        $collection = collect([TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ])]);

        $output = new CollectionOutput($collection, OutputType::ARRAY);

        $result = OutputCollectionPipeline::process($output);

        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net'
            ]
        ], $result->toArray());
    }

    public function testItGetArrayWrapped()
    {
        $collection = WrappedCollection::make([WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ])]);

        $output = new CollectionOutput($collection, OutputType::ARRAY);

        $result = OutputCollectionPipeline::process($output);

        $this->assertSame([
            'collection_wrapper' => [
                [
                    'wrapper' => [
                        'name' => 'Davey Shafik',
                        'age' => 40,
                    ]
                ]
            ]
        ], $result->toArray());
    }

    public function testItGetJson()
    {
        $collection = collect([TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ])]);

        $output = new CollectionOutput($collection, OutputType::JSON);

        $result = OutputCollectionPipeline::process($output);

        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
                'email' => 'davey@php.net'
            ]
        ], $result->toArray());
    }

    public function testItGetJsonWrapped()
    {
        $collection = WrappedCollection::make([WrappedJsonBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ])]);

        $output = new CollectionOutput($collection, OutputType::JSON);

        $result = OutputCollectionPipeline::process($output);

        $this->assertSame([
            'collection_json_wrapper' => [
                [
                    'wrapper' => [
                        'name' => 'Davey Shafik',
                        'age' => 40,
                    ]
                ]
            ]
        ], $result->jsonSerialize());
    }

    public function testItGetsUnwrapped()
    {
        $collection = WrappedCollection::make([WrappedJsonBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ])]);

        $output = new CollectionOutput($collection, OutputType::UNWRAPPED);

        $result = OutputCollectionPipeline::process($output);

        $this->assertSame([
            [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $result->toArray());
    }
}
