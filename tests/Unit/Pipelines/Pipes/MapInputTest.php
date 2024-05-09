<?php

declare(strict_types=1);

namespace Tests\Unit\Pipelines\Pipes;

use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\MappedInputNameClassBag;
use Tests\Fixtures\Values\MappedNameClassBag;
use Tests\TestCase;

#[CoversClass(MapInput::class)]
class MapInputTest extends TestCase
{
    public function testItMapsInputNamesBothMapped()
    {
        $input = new BagInput(MappedNameClassBag::class, collect([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new IsVariadic())($input);

        $pipe = new MapInput();
        $input = $pipe($input);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $input->values->toArray());
    }


    public function testItMapsInputNamesMultipleAliases()
    {
        $input = new BagInput(MappedNameClassBag::class, collect([
            'NAMEGOESHERE' => 'DAVEY SHAFIK',
            'name_goes_here' => 'Davey_Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new IsVariadic())($input);

        $pipe = new MapInput();
        $input = $pipe($input);

        $this->assertSame([
            'nameGoesHere' => 'Davey_Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $input->values->toArray());

        $input = new BagInput(MappedNameClassBag::class, collect([
            'name_goes_here' => 'Davey_Shafik',
            'NAMEGOESHERE' => 'DAVEY SHAFIK',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new IsVariadic())($input);

        $pipe = new MapInput();
        $input = $pipe($input);

        $this->assertSame([
            'nameGoesHere' => 'DAVEY SHAFIK',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $input->values->toArray());
    }

    public function testItMapsInputNames()
    {
        $input = new BagInput(MappedInputNameClassBag::class, collect([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new IsVariadic())($input);

        $pipe = new MapInput();
        $input = $pipe($input);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $input->values->toArray());
    }

    public function testItAllowsOriginalNames()
    {
        $input = new BagInput(MappedNameClassBag::class, collect([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]));
        $input = (new ProcessParameters())($input);
        $input = (new IsVariadic())($input);

        $pipe = new MapInput();
        $input = $pipe($input);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $input->values->toArray());
    }
}
