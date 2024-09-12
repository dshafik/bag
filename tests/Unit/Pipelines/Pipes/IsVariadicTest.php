<?php

declare(strict_types=1);
use Bag\Pipelines\Pipes\IsVariadic;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Values\BagInput;
use Tests\Fixtures\Values\TestBag;
use Tests\Fixtures\Values\VariadicBag;

covers(IsVariadic::class);

test('it is variadic', function () {
    $input = new BagInput(VariadicBag::class, collect());
    $input = (new ProcessParameters())($input);

    $pipe = new IsVariadic();
    $input = $pipe($input);

    expect($input->variadic)->toBeTrue();
});

test('it is not variadic', function () {
    $input = new BagInput(TestBag::class, collect());
    $input = (new ProcessParameters())($input);

    $pipe = new IsVariadic();
    $input = $pipe($input);

    expect($input->variadic)->toBeFalse();
});
