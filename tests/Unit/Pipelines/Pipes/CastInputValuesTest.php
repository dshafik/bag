<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Pipelines\Pipes\CastInputValues;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Carbon\CarbonImmutable;
use Illuminate\Support\Stringable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\CastInputOutputBag;
use Tests\Fixtures\Values\CastVariadicDatetimeBag;
use Tests\Fixtures\Values\VariadicBag;
use Tests\TestCase;

#[CoversClass(CastInputValues::class)]
class CastInputValuesTest extends TestCase
{
    public function testItCastsInputValues()
    {
        $input = new BagInput(CastInputOutputBag::class, collect([
            'input' => 'test',
            'output' => 'testing',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);

        $pipe = new CastInputValues();
        $input = $pipe($input);

        $this->assertInstanceOf(Stringable::class, $input->values->get('input'));
        $this->assertSame('TEST', $input->values->get('input')->toString());
    }

    public function testItDoesNotCastOutputValues()
    {
        $input = new BagInput(CastInputOutputBag::class, collect([
            'input' => 'test',
            'output' => 'testing',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);

        $pipe = new CastInputValues();
        $input = $pipe($input);

        $this->assertIsString($input->values->get('output'));
        $this->assertSame('testing', $input->values->get('output'));
    }

    public function testItCastsVariadics()
    {
        $input = new BagInput(CastVariadicDatetimeBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
            'test' => '2024-04-30',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);

        $pipe = new CastInputValues();
        $input = $pipe($input);

        $this->assertInstanceOf(CarbonImmutable::class, $input->values->get('test'));
        $this->assertSame('2024-04-30', $input->values->get('test')->format('Y-m-d'));
    }

    public function testItDoesNotCastsMixedVariadic()
    {
        $input = new BagInput(VariadicBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
            'test' => true,
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new MapInput())($input);

        $pipe = new CastInputValues();
        $input = $pipe($input);

        $this->assertTrue($input->values->get('test'));
    }
}
