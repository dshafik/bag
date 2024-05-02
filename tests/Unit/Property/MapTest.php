<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Bag\Attributes\MapName;
use Bag\Property\Map;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use Tests\Fixtures\MappedNameClassBag;
use Tests\Fixtures\MappedPropertyBag;
use Tests\TestCase;

#[CoversClass(Map::class)]
class MapTest extends TestCase
{
    public function testItMapsUsingClassAttribute()
    {
        $classMap = (new ReflectionClass(MappedNameClassBag::class))->getAttributes(MapName::class)[0]->newInstance();
        $property = (new ReflectionClass(MappedNameClassBag::class))->getProperty('nameGoesHere');


        $map = Map::create($classMap, $property);
        $this->assertSame('name_goes_here', $map->inputName);
        $this->assertSame('name_goes_here', $map->outputName);
    }

    public function testItMapsUsingPropertyAttributes()
    {
        $property = (new ReflectionClass(MappedPropertyBag::class))->getProperty('nameGoesHere');
        $map = Map::create(null, $property);
        $this->assertSame('name_goes_here', $map->inputName);
        $this->assertSame('name_goes_here', $map->outputName);

        $property = (new ReflectionClass(MappedPropertyBag::class))->getProperty('ageGoesHere');
        $map = Map::create(null, $property);
        $this->assertSame('age_goes_here', $map->inputName);
        $this->assertSame('age_goes_here', $map->outputName);

        $property = (new ReflectionClass(MappedPropertyBag::class))->getProperty('email_goes_here');
        $map = Map::create(null, $property);
        $this->assertSame('emailGoesHere', $map->inputName);
        $this->assertSame('emailGoesHere', $map->outputName);
    }

    public function testItMapsUsingClassAndPropertyAttributes()
    {
        $classMap = (new ReflectionClass(MappedNameClassBag::class))->getAttributes(MapName::class)[0]->newInstance();

        $property = (new ReflectionClass(MappedPropertyBag::class))->getProperty('nameGoesHere');
        $map = Map::create($classMap, $property);
        $this->assertSame('name_goes_here', $map->inputName);
        $this->assertSame('name_goes_here', $map->outputName);

        $property = (new ReflectionClass(MappedPropertyBag::class))->getProperty('ageGoesHere');
        $map = Map::create($classMap, $property);
        $this->assertSame('age_goes_here', $map->inputName);
        $this->assertSame('age_goes_here', $map->outputName);

        $property = (new ReflectionClass(MappedPropertyBag::class))->getProperty('email_goes_here');
        $map = Map::create($classMap, $property);
        $this->assertSame('emailGoesHere', $map->inputName);
        $this->assertSame('emailGoesHere', $map->outputName);
    }
}
