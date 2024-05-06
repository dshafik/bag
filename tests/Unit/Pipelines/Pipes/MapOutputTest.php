<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\MapOutput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\MappedOutputNameClassBag;
use Tests\TestCase;

#[CoversClass(MapOutput::class)]
class MapOutputTest extends TestCase
{
    public function testItMapsOutputNames()
    {
        $bag = MappedOutputNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]);

        $output = new BagOutput($bag, OutputType::JSON);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new MapOutput();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ], $output->output->toArray());
    }

    public function testItDoesNotMapRaw()
    {
        $bag = MappedOutputNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]);

        $output = new BagOutput($bag, OutputType::RAW);
        $output = (new ProcessProperties())($output, fn ($output) => $output);
        $output = (new ProcessParameters())($output, fn ($output) => $output);
        $output->values = $bag->getRaw();

        $pipe = new MapOutput();
        $output = $pipe($output, fn ($output) => $output);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $output->output->toArray());
    }
}
