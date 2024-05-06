<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Attributes\HiddenFromJson;
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\HideJsonValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\ComputedPropertyHiddenBag;
use Tests\Fixtures\Values\HiddenJsonParametersBag;
use Tests\TestCase;

#[CoversClass(HideJsonValues::class)]
#[CoversClass(HiddenFromJson::class)]
class HideJsonValuesTest extends TestCase
{
    public function testItDoesNotHideJsonUnlessOutputtingJson()
    {
        $bag = HiddenJsonParametersBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
            'passwordGoesHere' => 'hunter2',
        ]);

        $output = new BagOutput($bag, OutputType::ARRAY);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new HideJsonValues();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
            'passwordGoesHere' => 'hunter2',
        ], $output->values->toArray());
    }
    public function testItIgnoresHiddenPropertiesInJson()
    {
        $bag = HiddenJsonParametersBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
            'passwordGoesHere' => 'hunter2',
        ]);

        $output = new BagOutput($bag, OutputType::JSON);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new HideJsonValues();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'passwordGoesHere' => 'hunter2',
        ], $output->values->toArray());
    }

    public function testItHidesComputedProperties()
    {
        Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

        $bag = ComputedPropertyHiddenBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $output = new BagOutput($bag, OutputType::JSON);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new HideJsonValues();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $output->values->toArray());
    }
}
