<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Attributes\HiddenFromJson;
use Bag\Concerns\WithJson;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\HiddenJsonPropertiesBag;

#[CoversClass(WithJson::class)]
#[CoversClass(HiddenFromJson::class)]
class WithJsonTest extends TestCase
{
    public function testItIgnoresHiddenPropertiesInJson()
    {
        $value = HiddenJsonPropertiesBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
            'passwordGoesHere' => 'hunter2',
        ]);

        $this->assertSame('{"name_goes_here":"Davey Shafik","age_goes_here":40}', json_encode($value));
        $this->assertSame('{"name_goes_here":"Davey Shafik","age_goes_here":40}', $value->toJson());
    }
}
