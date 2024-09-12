<?php

declare(strict_types=1);

use Bag\Attributes\Wrap as WrapAttribute;
use Bag\Attributes\WrapJson as WrapJsonAttribute;
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\MapOutput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Pipes\Wrap;
use Bag\Pipelines\Values\BagOutput;
use Tests\Fixtures\Values\BagWithFactory;
use Tests\Fixtures\Values\WrappedBag;
use Tests\Fixtures\Values\WrappedBothBag;
use Tests\Fixtures\Values\WrappedJsonBag;

covers(Wrap::class, WrapAttribute::class, WrapJsonAttribute::class);

test('it does not wrap without wrap attribute', function () {
    $bag = BagWithFactory::factory()->make();

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();
    $output = (new MapOutput())($output);

    $pipe = new Wrap();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);
});

test('it does not wrap unwrapped', function () {
    $bag = WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::UNWRAPPED);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();
    $output = (new MapOutput())($output);

    $pipe = new Wrap();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);
});

test('it does not wrap raw', function () {
    $bag = WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::RAW);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();
    $output = (new MapOutput())($output);

    $pipe = new Wrap();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);
});

test('it wraps arrays', function () {
    $bag = WrappedBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();
    $output = (new MapOutput())($output);

    $pipe = new Wrap();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'wrapper' => [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]
    ]);
});

test('it wraps json', function () {
    $bag = WrappedJsonBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::JSON);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();
    $output = (new MapOutput())($output);

    $pipe = new Wrap();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'wrapper' => [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]
    ]);
});

test('it wraps both separately', function () {
    $bag = WrappedBothBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();
    $output = (new MapOutput())($output);

    $pipe = new Wrap();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'wrapper' => [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]
    ]);

    $output = new BagOutput($bag, OutputType::JSON);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();
    $output = (new MapOutput())($output);

    $pipe = new Wrap();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'json_wrapper' => [
            'name' => 'Davey Shafik',
            'age' => 40,
        ]
    ]);
});
