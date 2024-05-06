<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines;

use Bag\Pipelines\InputPipeline;
use Bag\Pipelines\Values\BagInput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(InputPipeline::class)]
#[CoversClass(BagInput::class)]
class InputPipelineTest extends TestCase
{
    public function testItCreatesBag()
    {
        $input = new BagInput(TestBag::class, [
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $bag = InputPipeline::process($input);

        $this->assertInstanceOf(TestBag::class, $bag);
    }
}
