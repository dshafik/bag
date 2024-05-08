<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Pipelines\Pipes\FillBag;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(FillBag::class)]
class FillBagTest extends TestCase
{
    public function testItCreatesBagInstance()
    {
        $input = new BagInput(TestBag::class, collect(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);

        $pipe = new FillBag();
        $input = $pipe($input);

        $this->assertInstanceOf(TestBag::class, $input->bag);
    }
}
