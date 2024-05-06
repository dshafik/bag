<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\ComputedPropertyBag;
use Tests\TestCase;

#[CoversClass(ProcessProperties::class)]
class ProcessPropertiesTest extends TestCase
{
    public function testItHandlesProperties()
    {
        $bag = ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessParameters())($output, fn (BagOutput$input) => $output);

        $pipe = new ProcessProperties();
        $output = $pipe($output, fn ($input) => $output);

        $this->assertInstanceOf(ValueCollection::class, $output->properties);
        $this->assertContainsOnlyInstancesOf(Value::class, $output->properties);
        $this->assertCount(1, $output->properties);
    }
}
