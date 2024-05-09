<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Bag\Property\Value;
use Bag\Property\ValueCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use ReflectionParameter;
use Tests\Fixtures\Values\BagWithTransformers;
use Tests\Fixtures\Values\MappedNameClassBag;
use Tests\Fixtures\Values\TestBag;
use Tests\TestCase;

#[CoversClass(ValueCollection::class)]
class ValueCollectionTest extends TestCase
{
    public function testItCreatesCollection()
    {
        $class = new ReflectionClass(TestBag::class);
        $collection = ValueCollection::make($class->getConstructor()?->getParameters())->mapWithKeys(function (ReflectionParameter $property) use ($class) {
            return [$property->getName() => Value::create($class, $property)];
        });

        $this->assertCount(3, $collection);
    }

    public function testItReturnsRequiredProperties()
    {
        $class = new ReflectionClass(BagWithTransformers::class);
        $collection = ValueCollection::make($class->getConstructor()?->getParameters())->mapWithKeys(function (ReflectionParameter $property) use ($class) {
            return [$property->getName() => Value::create($class, $property)];
        });

        $this->assertCount(4, $collection);
        $required = $collection->required();
        $this->assertCount(3, $required);
        $this->assertSame(['name', 'age', 'email'], $required->keys()->all());
    }

    public function testItResolvesAliases()
    {
        $class = new ReflectionClass(MappedNameClassBag::class);
        $collection = ValueCollection::make($class->getConstructor()?->getParameters())->mapWithKeys(function (ReflectionParameter $property) use ($class) {
            return [$property->getName() => Value::create($class, $property)];
        });

        $aliases = $collection->aliases();

        $this->assertSame([
            'input' => [
                'NAMEGOESHERE' => 'nameGoesHere',
                'name_goes_here' => 'nameGoesHere',
                'AGEGOESHERE' => 'ageGoesHere',
                'age_goes_here' => 'ageGoesHere',
                'EMAILGOESHERE' => 'emailGoesHere',
                'email_goes_here' => 'emailGoesHere',
            ],
            'output' => [
                'nameGoesHere' => 'name_goes_here',
                'ageGoesHere' => 'age_goes_here',
                'emailGoesHere' => 'email_goes_here',
            ],
        ], $aliases->toArray());
    }
}
