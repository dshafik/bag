<?php

declare(strict_types=1);
use Bag\Property\ValidatorCollection;
use Tests\Fixtures\Values\ValidateUsingAttributesBag;

test('it creates collection', function () {
    $property = (new \ReflectionClass(ValidateUsingAttributesBag::class))->getProperty('name');

    $collection = ValidatorCollection::create($property);

    expect($collection)
        ->toBeInstanceOf(ValidatorCollection::class)
        ->toContain('string')
        ->and($collection->all())->toBe(['required', 'string']);

});
