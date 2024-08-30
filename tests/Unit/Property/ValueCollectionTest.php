<?php

declare(strict_types=1);
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Tests\Fixtures\Values\BagWithTransformers;
use Tests\Fixtures\Values\MappedNameClassBag;
use Tests\Fixtures\Values\TestBag;

test('it creates collection', function () {
    $class = new \ReflectionClass(TestBag::class);
    $collection = ValueCollection::make($class->getConstructor()?->getParameters())->mapWithKeys(function (\ReflectionParameter $property) use ($class) {
        return [$property->getName() => Value::create($class, $property)];
    });

    expect($collection)->toHaveCount(3);
});

test('it returns required properties', function () {
    $class = new \ReflectionClass(BagWithTransformers::class);
    $collection = ValueCollection::make($class->getConstructor()?->getParameters())->mapWithKeys(function (\ReflectionParameter $property) use ($class) {
        return [$property->getName() => Value::create($class, $property)];
    });

    expect($collection)->toHaveCount(4);
    $required = $collection->required();
    expect($required)->toHaveCount(3)
        ->and($required->keys()->all())->toBe(['name', 'age', 'email']);
});

test('it resolves aliases', function () {
    $class = new \ReflectionClass(MappedNameClassBag::class);
    $collection = ValueCollection::make($class->getConstructor()?->getParameters())->mapWithKeys(function (\ReflectionParameter $property) use ($class) {
        return [$property->getName() => Value::create($class, $property)];
    });

    $aliases = $collection->aliases();

    expect($aliases->toArray())->toBe([
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
    ]);
});
