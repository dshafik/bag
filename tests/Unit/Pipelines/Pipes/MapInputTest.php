<?php

declare(strict_types=1);
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\MappedInputNameClassBag;
use Tests\Fixtures\Values\MappedNameClassBag;

covers(MapInput::class);

test('it maps input names both mapped', function () {
    $input = new BagInput(MappedNameClassBag::class, collect([
        'name_goes_here' => 'Davey Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new IsVariadic())($input);

    $pipe = new MapInput();
    $input = $pipe($input);

    expect($input->values->toArray())->toBe([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);
});

test('it maps input names multiple aliases', function () {
    $input = new BagInput(MappedNameClassBag::class, collect([
        'NAMEGOESHERE' => 'DAVEY SHAFIK',
        'name_goes_here' => 'Davey_Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new IsVariadic())($input);

    $pipe = new MapInput();
    $input = $pipe($input);

    expect($input->values->toArray())->toBe([
        'nameGoesHere' => 'Davey_Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);

    $input = new BagInput(MappedNameClassBag::class, collect([
        'name_goes_here' => 'Davey_Shafik',
        'NAMEGOESHERE' => 'DAVEY SHAFIK',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new IsVariadic())($input);

    $pipe = new MapInput();
    $input = $pipe($input);

    expect($input->values->toArray())->toBe([
        'nameGoesHere' => 'DAVEY SHAFIK',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);
});

test('it maps input names', function () {
    $input = new BagInput(MappedInputNameClassBag::class, collect([
        'name_goes_here' => 'Davey Shafik',
        'age_goes_here' => 40,
        'email_goes_here' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new IsVariadic())($input);

    $pipe = new MapInput();
    $input = $pipe($input);

    expect($input->values->toArray())->toBe([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);
});

test('it allows original names', function () {
    $input = new BagInput(MappedNameClassBag::class, collect([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]));
    $input = (new ProcessParameters())($input);
    $input = (new IsVariadic())($input);

    $pipe = new MapInput();
    $input = $pipe($input);

    expect($input->values->toArray())->toBe([
        'nameGoesHere' => 'Davey Shafik',
        'ageGoesHere' => 40,
        'emailGoesHere' => 'davey@php.net',
    ]);
});
