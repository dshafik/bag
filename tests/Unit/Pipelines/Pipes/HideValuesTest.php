<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Attributes\Hidden;
use Bag\Enums\OutputType;
use Bag\Internal\Cache;
use Bag\Pipelines\Pipes\HideValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\ComputedPropertyHiddenBag;
use Tests\Fixtures\Values\HiddenParametersBag;
use Tests\TestCase;

#[CoversClass(HideValues::class)]
#[CoversClass(Hidden::class)]
class HideValuesTest extends TestCase
{
    public function testItIgnoresHiddenProperties()
    {
        $bag = HiddenParametersBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();

        $pipe = new HideValues();
        $output = $pipe($output);

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $output->values->toArray());
    }

    public function testItHidesComputedProperties()
    {
        Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

        $bag = ComputedPropertyHiddenBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();

        $pipe = new HideValues();
        $output = $pipe($output);

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $output->values->toArray());
    }

    public function testItUsesCache()
    {
        Cache::fake()->shouldReceive('store')->atLeast()->twice()->passthru();

        $bag = HiddenParametersBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output->values = $bag->getRaw();

        $pipe = new HideValues();
        $pipe($output);
        $pipe($output);
    }
}
