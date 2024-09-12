<?php

declare(strict_types=1);
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Tests\Fixtures\Values\ComputedPropertyBag;

covers(ProcessParameters::class);

test('it handles properties', function () {
    $bag = ComputedPropertyBag::from(['name' => 'Davey Shafik', 'age' => 40]);
    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessParameters())($output, fn (BagOutput$input) => $output);

    $pipe = new ProcessProperties();
    $output = $pipe($output, fn ($input) => $output);

    expect($output->properties)
        ->toBeInstanceOf(ValueCollection::class)
        ->toContainOnlyInstancesOf(Value::class)
        ->toHaveCount(1);
});
