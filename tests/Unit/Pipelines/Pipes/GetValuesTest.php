<?php

declare(strict_types=1);
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\GetValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Tests\Fixtures\Values\MappedOutputNameClassBag;

covers(GetValues::class);

test('it gets values', function () {
    $bag = MappedOutputNameClassBag::from([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);

    $output = new BagOutput($bag, OutputType::JSON);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output = (new GetValues())($output);

    expect($output->values->toArray())->toBe($bag->getRaw()->toArray());
});
