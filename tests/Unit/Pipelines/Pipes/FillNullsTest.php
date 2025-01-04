<?php

declare(strict_types=1);

use Bag\Pipelines\Pipes\FillNulls;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\NullablePropertiesBag;
use Tests\Fixtures\Values\OptionalPropertiesWithDefaultsBag;

covers(FillNulls::class);

test('fill nulls', function () {
    $input = new BagInput(NullablePropertiesBag::class, collect());
    $input = (new ProcessParameters())($input);

    $pipe = new FillNulls();
    $input = $pipe($input);

    expect($input->input->toArray())->toBe(['name' => null, 'age' => null, 'email' => null, 'bag' => null]);
});

test('do not fill defaults', function () {
    $input = new BagInput(OptionalPropertiesWithDefaultsBag::class, collect());
    $input = (new ProcessParameters())($input);

    $pipe = new FillNulls();
    $input = $pipe($input);

    expect($input->input->toArray())->toBeEmpty();
});
