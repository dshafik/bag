<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Exceptions\MissingPropertiesException;
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\MissingParameters;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\OptionalPropertiesBag;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(MissingParameters::class)]
#[CoversClass(MissingPropertiesException::class)]
class MissingParametersTest extends TestCase
{
    public function testItDoesNotErrorWithoutMissingParamaters()
    {
        $input = new BagInput(TestBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new MissingParameters();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(BagInput::class, $input);
    }

    public function testItDoesNotErrorWithMissingOptionalParameters()
    {
        $input = new BagInput(OptionalPropertiesBag::class, collect());
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new MissingParameters();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(BagInput::class, $input);

        $input = new BagInput(OptionalPropertiesBag::class, collect([
            'name' => 'Davey Shafik',
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $pipe = new MissingParameters();
        $input = $pipe($input, fn ($input) => $input);

        $this->assertInstanceOf(BagInput::class, $input);
    }

    public function testItErrorsWithMissingParameters()
    {
        $input = new BagInput(TestBag::class, collect([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]));
        $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
        $input = (new MapInput())($input, fn (BagInput $input) => $input);
        $input = (new IsVariadic())($input, fn (BagInput $input) => $input);

        $this->expectException(MissingPropertiesException::class);
        $this->expectExceptionMessage('Missing required properties: email');
        $pipe = new MissingParameters();
        $pipe($input, fn ($input) => $input);
    }
}
