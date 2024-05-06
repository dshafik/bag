<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Concerns\WithJson;
use Bag\Internal\Cache;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\HiddenJsonParametersBag;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(WithJson::class)]
class WithJsonTest extends TestCase
{
    public function testItEncodesJson()
    {
        $value = TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
        ]);

        $this->assertSame('{"name":"Davey Shafik","age":40,"email":"davey@php.net"}', json_encode($value));
        $this->assertSame('{"name":"Davey Shafik","age":40,"email":"davey@php.net"}', $value->toJson());
    }

    public function testItUsesCache()
    {
        Cache::fake()->shouldReceive('store')->atLeast()->times(2)->passthru();

        $value = HiddenJsonParametersBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
            'passwordGoesHere' => 'hunter2',
        ]);

        $this->assertSame('{"name_goes_here":"Davey Shafik","age_goes_here":40}', json_encode($value));
        $this->assertSame('{"name_goes_here":"Davey Shafik","age_goes_here":40}', $value->toJson());
    }
}
