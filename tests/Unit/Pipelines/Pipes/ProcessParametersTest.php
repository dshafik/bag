<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\NoConstructorBag;
use Tests\Fixtures\Values\NoPropertiesBag;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(ProcessParameters::class)]
class ProcessParametersTest extends TestCase
{
    public function testItRequiresAConstructor()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Bag "Tests\Fixtures\Values\NoConstructorBag" must have a constructor with at least one parameter');

        $input = new BagInput(NoConstructorBag::class, collect(['foo' => 'bar']));

        $pipe = new ProcessParameters();
        $pipe($input);
    }

    public function testItRequiresBagParameters()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Bag "Tests\Fixtures\Values\NoPropertiesBag" must have a constructor with at least one parameter');

        $input = new BagInput(NoPropertiesBag::class, collect(['foo' => 'bar']));

        $pipe = new ProcessParameters();
        $pipe($input);
    }

    public function testItHandlesParameters()
    {
        $input = new BagInput(TestBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]));

        $pipe = new ProcessParameters();
        $input = $pipe($input);

        $this->assertInstanceOf(ValueCollection::class, $input->params);
        $this->assertContainsOnlyInstancesOf(Value::class, $input->params);
        $this->assertCount(3, $input->params);
    }
}
