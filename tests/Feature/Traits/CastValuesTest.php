<?php

declare(strict_types=1);

namespace Tests\Feature\Traits;

use Bag\Attributes\Cast;
use Bag\Attributes\CastInput as CastInputAttribute;
use Bag\Attributes\CastOutput as CastOutputAttribute;
use Bag\Property\CastInput;
use Bag\Property\CastOutput;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\Fixtures\Values\CastInputOutputBag;
use Tests\Fixtures\Values\CastsDateBag;
use Tests\Fixtures\Values\CastsDateInputBag;
use Tests\Fixtures\Values\CastsDateOutputBag;
use Tests\Fixtures\Values\CastVariadicCollectionBag;
use Tests\Fixtures\Values\CastVariadicDatetimeBag;
use Tests\Fixtures\Values\TypedVariadicBag;
use Tests\Fixtures\Values\VariadicBag;
use Tests\TestCase;

#[CoversClass(Cast::class)]
#[CoversClass(CastInput::class)]
#[CoversClass(CastOutput::class)]
#[CoversClass(CastInputAttribute::class)]
#[CoversClass(CastOutputAttribute::class)]
class CastValuesTest extends TestCase
{
    use WithFaker;

    public function testItDoesNotCastInput()
    {
        $value = CastInputOutputBag::from([
            'input' => 'test',
            'output' => 'testing',
        ]);

        $this->assertSame('TEST', $value->input->toString());
        $this->assertSame('testing', $value->output);
        $this->assertSame('TESTING', $value->toArray()['output']);
    }

    public function testItCastsInputAndOutput()
    {
        $value = CastsDateBag::from(['date' => '2024-04-12 12:34:56']);

        $this->assertSame('2024-04-12', $value->date->format('Y-m-d'));
        $this->assertSame('2024-04-12', $value->toArray()['date']);
    }

    public function testItCastsInput()
    {
        $value = CastsDateInputBag::from(['date' => '2024-04-12 12:34:56']);

        $this->assertSame('2024-04-12', $value->date->format('Y-m-d'));
        $this->assertInstanceOf(CarbonImmutable::class, $value->toArray()['date']);
    }

    public function testItCastsOutput()
    {
        $value = CastsDateOutputBag::from(['date' => new CarbonImmutable('2024-04-12 12:34:56')]);

        $this->assertSame('2024-04-12', $value->date->format('Y-m-d'));
        $this->assertSame('2024-04-12', $value->toArray()['date']);
    }

    public function testItDoesNotCastMixedVariadics()
    {
        $value = VariadicBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'extra' => true,
        ]);

        $this->assertSame('Davey Shafik', $value->name);
        $this->assertSame(40, $value->age);
        $this->assertTrue($value->values['extra']);
    }

    public function testItCastsTypedVariadics()
    {
        $value = TypedVariadicBag::from([
            'name' => 'Davey Shafik',
            'age' => 40,
            'extra' => 1,
        ]);

        $this->assertSame('Davey Shafik', $value->name);
        $this->assertSame(40, $value->age);
        $this->assertTrue($value->values['extra']);
    }

    public function testIsCastsVariadicsCollections()
    {
        $extra = [
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
            ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)],
        ];
        $more = [['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)]];
        $value = CastVariadicCollectionBag::from([
            'name' => 'Davey Shafik',
            'age' => '40',
            'extra' => $extra,
            'more' => $more,
        ]);

        $this->assertContainsOnlyInstancesOf(BagWithCollection::class, $value->values['extra']);
        $this->assertCount(2, $value->values['extra']);
        $this->assertContainsOnlyInstancesOf(BagWithCollection::class, $value->values['more']);
        $this->assertCount(1, $value->values['more']);

        $this->assertSame($extra, $value->values['extra']->toArray());
        $this->assertSame($more, $value->values['more']->toArray());
    }

    public function testIsCastsVariadicsDatetime()
    {
        $value = CastVariadicDatetimeBag::from([
            'name' => 'Davey Shafik',
            'age' => '40',
            'extra' => '2024-04-30',
            'more' => '2024-05-31',
        ]);

        $this->assertContainsOnlyInstancesOf(CarbonImmutable::class, $value->values);
        $this->assertCount(2, $value->values);

        $this->assertSame('2024-04-30', $value->values['extra']->format('Y-m-d'));
        $this->assertSame('2024-05-31', $value->values['more']->format('Y-m-d'));
    }
}
