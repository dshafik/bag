<?php

declare(strict_types=1);

use Bag\Attributes\Hidden;
use Bag\Enums\OutputType;
use Bag\Internal\Cache;
use Bag\Pipelines\Pipes\HideValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Tests\Fixtures\Values\ComputedPropertyHiddenBag;
use Tests\Fixtures\Values\HiddenParametersBag;

covers(HideValues::class, Hidden::class);

test('it ignores hidden properties', function () {
    $bag = HiddenParametersBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'name' => 'Davey Shafik',
    ]);
});

test('it hides computed properties', function () {
    Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

    $bag = ComputedPropertyHiddenBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);
});

test('it uses cache', function () {
    Cache::fake()->shouldReceive('store')->atLeast()->twice()->passthru();

    $bag = HiddenParametersBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'email' => 'davey@php.net',
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideValues();
    $pipe($output);
    $pipe($output);
});
