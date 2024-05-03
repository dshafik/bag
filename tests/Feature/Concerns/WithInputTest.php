<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Attributes\MapName;
use Bag\Concerns\WithInput;
use Bag\Concerns\WithOutput;
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Exceptions\MissingPropertiesException;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\MappedInputNameClassBag;
use Tests\Fixtures\Values\MappedNameClassBag;
use Tests\Fixtures\Values\TestBag;

#[CoversClass(WithInput::class)]
#[CoversClass(WithOutput::class)]
#[CoversClass(MapName::class)]
#[CoversClass(AdditionalPropertiesException::class)]
#[CoversClass(MissingPropertiesException::class)]
class WithInputTest extends TestCase
{
    public function testItMapsNames()
    {
        $value = MappedNameClassBag::from([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
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

    public function testItMapsInputNames()
    {
        $value = MappedInputNameClassBag::from([
            'name_goes_here' => 'Davey Shafik',
            'age_goes_here' => 40,
            'email_goes_here' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->nameGoesHere);
        $this->assertSame(40, $value->ageGoesHere);
        $this->assertSame('davey@php.net', $value->emailGoesHere);

        $this->assertSame([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ], $value->toArray());
    }

    public function testItAllowsOriginalNames()
    {
        $value = MappedNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net',
        ]);

        $this->assertSame('Davey Shafik', $value->nameGoesHere);
        $this->assertSame(40, $value->ageGoesHere);
        $this->assertSame('davey@php.net', $value->emailGoesHere);
    }

    public function testItErrorsOnAdditionalProperties()
    {
        $this->expectException(AdditionalPropertiesException::class);
        $this->expectExceptionMessage('Additional properties found: foo, baz');

        TestBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net',
            'foo' => 'bar',
            'baz' => 'bat',
        ]);
    }

    public function testItErrorsOnMissingProperties()
    {
        $this->expectException(MissingPropertiesException::class);
        $this->expectExceptionMessage('Missing required properties: age, email');

        TestBag::from([
            'name' => 'Davey Shafik',
        ]);
    }
}
