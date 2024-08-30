<?php

declare(strict_types=1);
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\MapOutput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Tests\Fixtures\Values\MappedOutputNameClassBag;

test('it maps output names', function () {
    $bag = MappedOutputNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);

    $output = new BagOutput($bag, OutputType::JSON);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new MapOutput();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'name_goes_here' => 'Davey Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]);
});

test('it does not map raw', function () {
    $bag = MappedOutputNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);

    $output = new BagOutput($bag, OutputType::RAW);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new MapOutput();
    $output = $pipe($output);

    expect($output->output->toArray())->toBe([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);
});
