<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\CastOutputValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\CastInputOutputBag;
use Tests\Fixtures\Values\CastVariadicDatetimeBag;
use Tests\Fixtures\Values\VariadicBag;
use Tests\TestCase;

#[CoversClass(CastOutputValues::class)]
class CastOutputValuesTest extends TestCase
{
    public function testItCastsOutputValues()
    {
        $bag = CastInputOutputBag::from([
            'input' => 'test',
            'output' => 'testing',
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new CastOutputValues();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertIsString($output->values->get('output'));
        $this->assertSame('TESTING', $output->values->get('output'));
    }

    public function testItDoesNotCastMixedVariadicOutput()
    {
        $bag = VariadicBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'test' => 'testing'
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new CastOutputValues();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertIsArray($output->values->get('values'));
        $this->assertArrayHasKey('test', $output->values->get('values'));
        $this->assertSame('testing', $output->values->get('values')['test']);
    }

    public function testItCastsVariadicOutput()
    {
        $bag = CastVariadicDatetimeBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'test' => new CarbonImmutable('2024-04-30')
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new CastOutputValues();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertIsArray($output->values->get('values'));
        $this->assertArrayHasKey('test', $output->values->get('values'));
        $this->assertSame('2024-04-30', $output->values->get('values')['test']);
    }
}
