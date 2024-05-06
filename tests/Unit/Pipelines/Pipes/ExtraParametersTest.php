<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Pipelines\Pipes\ExtraParameters;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\VariadicBag;
use Tests\TestCase;

#[CoversClass(ExtraParameters::class)]
#[CoversClass(AdditionalPropertiesException::class)]
class ExtraParametersTest extends TestCase
{
    public function testItDoesNotErrorWithoutExtraParamaters()
    {
        $input = new BagInput(TestBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new ExtraParameters();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(BagInput::class, $input);
    }

    public function testItDoesNotErrorWithVariadicBagWithExtraParameters()
    {
        $input = new BagInput(VariadicBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new ExtraParameters();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(BagInput::class, $input);
    }

    public function testItErrorsOnNonVariadicWithExtraParameters()
    {
        $input = new BagInput(TestBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
            'test' => true,
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $this->expectException(AdditionalPropertiesException::class);
        $this->expectExceptionMessage('Additional properties found: test');
        $pipe = new ExtraParameters();
        $pipe($input, fn ($input) => $input);
    }
}
