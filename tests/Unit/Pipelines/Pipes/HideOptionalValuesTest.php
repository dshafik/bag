<?php

declare(strict_types=1);

use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\HideOptionalValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Tests\Fixtures\Values\BagWithOptionals;

test('it hides optionals when Optional', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik']);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideOptionalValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'name' => 'Davey Shafik',
    ]);
});

test('it does not hide optionals when not Optional', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideOptionalValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);
});

test('it does not hide optionals when null', function () {
    $bag = BagWithOptionals::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => null]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideOptionalValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => null,
    ]);
});
