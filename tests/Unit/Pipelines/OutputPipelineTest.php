<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines;

use Bag\Enums\OutputType;
use Bag\Pipelines\OutputPipeline;
use Bag\Pipelines\Values\BagOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\WrappedBag;
use Tests\Fixtures\Values\WrappedBothBag;
use Tests\Fixtures\Values\WrappedJsonBag;
use Tests\TestCase;

#[CoversClass(BagOutput::class)]
#[CoversClass(OutputPipeline::class)]
class OutputPipelineTest extends TestCase
{
    public function testItGetArray()
    {
        $bag = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ], $result);
    }

    public function testItGetArrayWrapped()
    {
        $bag = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $result);
    }

    public function testItGetsJson()
    {
        $bag = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $output = new BagOutput($bag, OutputType::JSON);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ], $result);
    }


    public function testItGetJsonWrapped()
    {
        $bag = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::JSON);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $result);
    }

    public function testItGetsBothWrapped()
    {
        $bag = WrappedBothBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $result);

        $output = new BagOutput($bag, OutputType::JSON);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'json_wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $result);
    }

    public function testItGetJsonOnlyWrapped()
    {
        $bag = WrappedJsonBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::JSON);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $result);
    }

    public function testItGetsUnwrapped()
    {
        $bag = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::UNWRAPPED);

        $result = OutputPipeline::process($output);

        $this->assertIsArray($result);
        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $result);
    }
}
