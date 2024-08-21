<?php

declare(strict_types=1);

namespace Tests\Feature\Traits;

use Bag\Attributes\Bag;
use Bag\Exceptions\BagAttributeNotFoundException;
use Bag\Exceptions\BagNotFoundException;
use Bag\Traits\HasBag;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use Tests\Fixtures\ObjectToBagAll;
use Tests\Fixtures\ObjectToBagInvalidBag;
use Tests\Fixtures\ObjectToBagNoAttribute;
use Tests\Fixtures\ObjectToBagProtected;
use Tests\Fixtures\ObjectToBagPublic;
use Tests\Fixtures\Values\ObjectToBagPrivate;
use Tests\Fixtures\Values\OptionalPropertiesBag;
use Tests\TestCase;

#[CoversTrait(HasBag::class)]
#[CoversClass(BagNotFoundException::class)]
#[CoversClass(BagAttributeNotFoundException::class)]
#[CoversClass(Bag::class)]
class HasBagTest extends TestCase
{
    public function testItCreatesBagWithPublicProperties(): void
    {
        $object = new ObjectToBagPublic('Davey Shafik', 40, 'davey@php.net');

        /** @var OptionalPropertiesBag $bag */
        $bag = $object->toBag();
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertNull($bag->age);
        $this->assertNull($bag->email);
    }

    public function testItCreatesBagWithPublicAndProtectedProperties(): void
    {
        $object = new ObjectToBagProtected('Davey Shafik', 40, 'davey@php.net');

        /** @var OptionalPropertiesBag $bag */
        $bag = $object->toBag();
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertNull($bag->email);
    }

    public function testItCreatesBagWithPublicProtectedAndPrivateProperties(): void
    {
        $object = new ObjectToBagAll('Davey Shafik', 40, 'davey@php.net');

        /** @var OptionalPropertiesBag $bag */
        $bag = $object->toBag();
        $this->assertSame('Davey Shafik', $bag->name);
        $this->assertSame(40, $bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItCreatesBagWithPrivateProperties(): void
    {
        $object = new ObjectToBagPrivate('Davey Shafik', 40, 'davey@php.net');

        /** @var OptionalPropertiesBag $bag */
        $bag = $object->toBag();
        $this->assertNull($bag->name);
        $this->assertNull($bag->age);
        $this->assertSame('davey@php.net', $bag->email);
    }

    public function testItErrorsWhenNoAttributeFound()
    {
        $this->expectException(BagAttributeNotFoundException::class);
        $this->expectExceptionMessage('Bag attribute not found on class ' . ObjectToBagNoAttribute::class);


        (new ObjectToBagNoAttribute())->toBag();
    }

    public function testItErrorsWhenBagDoesNotExist()
    {
        $this->expectException(BagNotFoundException::class);
        $this->expectExceptionMessage('The Bag class "InvalidBagName" does not exist');


        (new ObjectToBagInvalidBag())->toBag();
    }
}
