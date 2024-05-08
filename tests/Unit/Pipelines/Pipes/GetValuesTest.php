<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\GetValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\MappedOutputNameClassBag;
use Tests\TestCase;

#[CoversClass(GetValues::class)]
class GetValuesTest extends TestCase
{
    public function testItGetsValues()
    {
        $bag = MappedOutputNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]);

        $output = new BagOutput($bag, OutputType::JSON);
        $output = (new ProcessProperties())($output);
        $output = (new ProcessParameters())($output);
        $output = (new GetValues())($output);

        $this->assertSame($bag->getRaw()->toArray(), $output->values->toArray());
    }
}
