<?php

declare(strict_types=1);

namespace Tests\Feature\Traits;

use Bag\Concerns\WithOutput;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\HiddenParametersBag;
use Tests\Fixtures\Values\MappedOutputNameClassBag;
use Tests\Fixtures\Values\WrappedBag;
use Tests\TestCase;

#[CoversClass(WithOutput::class)]
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

    public function testItGetsValues()
    {
        $value = MappedOutputNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net'
        ]);

        $values = $value->get();

        $this->assertSame('Davey Shafik', $values['nameGoesHere']);
        $this->assertSame(40, $values['ageGoesHere']);
        $this->assertSame('davey@php.net', $values['emailGoesHere']);
    }

    public function testItGetsValuesWithoutHidden()
    {
        $value = HiddenParametersBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $values = $value->get();

        $this->assertSame('Davey Shafik', $values['name']);
        $this->assertSame(40, $values['age']);
        $this->assertArrayNotHasKey('email', $values);
    }

    public function testItGetsValue()
    {
        $value = MappedOutputNameClassBag::from([
            'nameGoesHere' => 'Davey Shafik',
            'ageGoesHere' => 40,
            'emailGoesHere' => 'davey@php.net'
        ]);

        $name = $value->get('nameGoesHere');

        $this->assertSame('Davey Shafik', $name);
    }

    public function testItDoesNotGetHiddenValue()
    {
        $value = HiddenParametersBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $value = $value->get('email');

        $this->assertNull($value);
    }

    public function testItGetsRawValues()
    {
        $value = HiddenParametersBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $values = $value->getRaw();

        $this->assertSame('Davey Shafik', $values['name']);
        $this->assertSame(40, $values['age']);
        $this->assertSame('davey@php.net', $values['email']);
    }

    public function testItGetsRawValue()
    {
        $value = HiddenParametersBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'email' => 'davey@php.net'
        ]);

        $email = $value->getRaw('email');

        $this->assertSame('davey@php.net', $email);
    }

    public function testItGetsUnwrapped()
    {
        $value = WrappedBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);

        $this->assertSame([
            'name' => 'Davey Shafik',
            'age' => 40,
        ], $value->unwrapped());
    }
}
