<?php

declare(strict_types=1);

use Bag\Attributes\HiddenFromJson;
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\HideJsonValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Tests\Fixtures\Values\ComputedPropertyHiddenBag;
use Tests\Fixtures\Values\HiddenJsonParametersBag;

covers(HideJsonValues::class, HiddenFromJson::class);

test('it does not hide json unless outputting json', function () {
    $bag = HiddenJsonParametersBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
        'passwordGoesHere' => 'hunter2',
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideJsonValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
        'passwordGoesHere' => 'hunter2',
    ]);
});

test('it ignores hidden properties in json', function () {
    $bag = HiddenJsonParametersBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
        'passwordGoesHere' => 'hunter2',
    ]);

    $output = new BagOutput($bag, OutputType::JSON);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideJsonValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'passwordGoesHere' => 'hunter2',
    ]);
});

test('it hides computed properties', function () {
    Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));

    $bag = ComputedPropertyHiddenBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);

    $output = new BagOutput($bag, OutputType::JSON);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new HideJsonValues();
    $output = $pipe($output);

    expect($output->values->toArray())->toBe([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]);
});
