<?php

declare(strict_types=1);

namespace Tests\Unit\Property;

use Bag\Property\ValidatorCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use ReflectionClass;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;
use Tests\TestCase;

#[CoversClass(ValidatorCollection::class)]
class ValidatorCollectionTest extends TestCase
{
    public function testItCreatesCollection()
    {
        $property = (new ReflectionClass(ValidateUsingAttributesBag::class))->getProperty('name');

        $collection = ValidatorCollection::create($property);

        $this->assertInstanceOf(ValidatorCollection::class, $collection);
        $this->assertContainsOnly('string', $collection);

        $this->assertSame(['required', 'string'], $collection->all());
    }
}
