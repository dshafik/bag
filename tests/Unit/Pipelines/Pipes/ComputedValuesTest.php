<?php

declare(strict_types=1);
use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Pipelines\Pipes\ComputedValues;
use Bag\Pipelines\Pipes\MapInput;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Tests\Fixtures\Values\ComputedPropertyBag;
use Tests\Fixtures\Values\ComputedPropertyMissingBag;

covers(ComputedValues::class);

test('it validates computed exists', function () {
    $input = new BagInput(ComputedPropertyBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);

    /** @var BagInput $input */
    Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));
    $input->bag = new ComputedPropertyBag($input->values->get('name'), $input->values->get('age'));

    $pipe = new ComputedValues();
    $input = $pipe($input);

    expect($input->bag->dob->format('Y-m-d'))->toBe('1984-05-04');
});

test('it errors when computed not set', function () {
    $input = new BagInput(ComputedPropertyMissingBag::class, collect([
        'name' => 'Davey Shafik',
        'age' => 40,
    ]));
    $input = (new ProcessParameters())($input, fn (BagInput $input) => $input);
    $input = (new MapInput())($input, fn (BagInput $input) => $input);

    /** @var BagInput $input */
    Carbon::setTestNow(new CarbonImmutable('2024-05-04 14:43:23'));
    $input->bag = new ComputedPropertyMissingBag($input->values->get('name'), $input->values->get('age'));

    $pipe = new ComputedValues();
    $pipe($input);
})->throws(ComputedPropertyUninitializedException::class, 'Property Tests\Fixtures\Values\ComputedPropertyMissingBag->dob must be computed');
