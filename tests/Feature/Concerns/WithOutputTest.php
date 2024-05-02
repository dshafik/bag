<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Attributes\MapName;
use Bag\Concerns\WithOutput;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\MappedOutputNameClassBag;

#[CoversClass(WithOutput::class)]
#[CoversClass(MapName::class)]
class WithOutputTest extends TestCase
{
    public function testItMapsOutputNames()
    {
        $value = MappedOutputNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->nameGoesHere);
        $this->assertSame(40, $value->ageGoesHere);
        $this->assertSame('davey@php.net', $value->emailGoesHere);

        $this->assertSame([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ], $value->toArray());
    }
}
