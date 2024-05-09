<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Bag\Property\MapCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use Tests\Fixtures\Values\MappedClassAndPropertyBag;
use Tests\TestCase;

#[CoversClass(MapCollection::class)]
class MapCollectionTest extends TestCase
{
    public function testItCreatesCollection()
    {
        $class = new ReflectionClass(MappedClassAndPropertyBag::class);
        $property = $class->getProperty('nameGoesHere');

        $collection = MapCollection::create($class, $property);

        $this->assertInstanceOf(MapCollection::class, $collection);

        $this->assertSame([
            'input' => ['name_goes_here'],
            'output' => 'name_goes_here',
        ], $collection->toArray());
    }
}
