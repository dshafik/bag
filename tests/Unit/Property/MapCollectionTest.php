<?php

declare(strict_types=1);
use Bag\Property\MapCollection;
use Tests\Fixtures\Values\MappedClassAndPropertyBag;

test('it creates collection', function () {
    $class = new \ReflectionClass(MappedClassAndPropertyBag::class);
    $property = $class->getProperty('nameGoesHere');

    $collection = MapCollection::create($class, $property);

    expect($collection)->toBeInstanceOf(MapCollection::class)
        ->and($collection->toArray())->toBe([
            'input' => ['name_goes_here'],
            'output' => 'name_goes_here',
        ]);

});
