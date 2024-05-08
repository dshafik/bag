<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Attributes\Wrap as WrapAttribute;
use Bag\Attributes\WrapJson as WrapJsonAttribute;
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\MapOutput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Pipes\Wrap;
use Bag\Pipelines\Values\BagOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\BagWithFactory;
use Tests\Fixtures\Values\WrappedBag;
use Tests\Fixtures\Values\WrappedBothBag;
use Tests\Fixtures\Values\WrappedJsonBag;
use Tests\TestCase;

#[CoversClass(Wrap::class)]
#[CoversClass(WrapAttribute::class)]
#[CoversClass(WrapJsonAttribute::class)]
class WrapTest extends TestCase
{
    public function testItDoesNotWrapWithoutWrapAtrribute()
    {
        $bag = BagWithFactory::factory()->make();

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();
        $output = (new MapOutput())($output);

        $pipe = new Wrap();
        $output = $pipe($output);

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $output->output->toArray());
    }

    public function testItDoesNotWrapUnwrapped()
    {
        $bag = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::UNWRAPPED);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();
        $output = (new MapOutput())($output);

        $pipe = new Wrap();
        $output = $pipe($output);

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $output->output->toArray());
    }

    public function testItDoesNotWrapRaw()
    {
        $bag = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::RAW);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();
        $output = (new MapOutput())($output);

        $pipe = new Wrap();
        $output = $pipe($output);

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $output->output->toArray());
    }

    public function testItWrapsArrays()
    {
        $bag = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();
        $output = (new MapOutput())($output);

        $pipe = new Wrap();
        $output = $pipe($output);

        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $output->output->toArray());
    }

    public function testItWrapsJson()
    {
        $bag = WrappedJsonBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::JSON);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();
        $output = (new MapOutput())($output);

        $pipe = new Wrap();
        $output = $pipe($output);

        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $output->output->toArray());
    }

    public function testItWrapsBothSeparately()
    {
        $bag = WrappedBothBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();
        $output = (new MapOutput())($output);

        $pipe = new Wrap();
        $output = $pipe($output);

        $this->assertSame([
            'wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $output->output->toArray());

        $output = new BagOutput($bag, OutputType::JSON);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();
        $output = (new MapOutput())($output);

        $pipe = new Wrap();
        $output = $pipe($output);

        $this->assertSame([
            'json_wrapper' => [
                'name' => 'Davey Shafik',
                'age' => 40,
            ]
        ], $output->output->toArray());
    }
}
