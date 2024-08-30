<?php

declare(strict_types=1);
use Bag\Enums\OutputType;
use Bag\Pipelines\Pipes\CastOutputValues;
use Bag\Pipelines\Pipes\ProcessParameters;
use Bag\Pipelines\Pipes\ProcessProperties;
use Bag\Pipelines\Values\BagOutput;
use Carbon\CarbonImmutable;
use Tests\Fixtures\Values\CastInputOutputBag;
use Tests\Fixtures\Values\CastVariadicDatetimeBag;
use Tests\Fixtures\Values\VariadicBag;

test('it casts output values', function () {
    $bag = CastInputOutputBag::from([
        'input' => 'test',
        'output' => 'testing',
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new CastOutputValues();
    $output = $pipe($output);

    expect($output->values->get('output'))->toBeString()
        ->and($output->values->get('output'))->toBe('TESTING');
});

test('it does not cast mixed variadic output', function () {
    $bag = VariadicBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'test' => 'testing'
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new CastOutputValues();
    $output = $pipe($output);

    expect($output->values->get('values'))->toBeArray()
        ->and($output->values->get('values'))->toHaveKey('test')
        ->and($output->values->get('values')['test'])->toBe('testing');
});

test('it casts variadic output', function () {
    $bag = CastVariadicDatetimeBag::from([
        'name' => 'Davey Shafik',
        'age' => 40,
        'test' => new CarbonImmutable('2024-04-30')
    ]);

    $output = new BagOutput($bag, OutputType::ARRAY);
    $output = (new ProcessProperties())($output);
    $output = (new ProcessParameters())($output);
    $output->values = $bag->getRaw();

    $pipe = new CastOutputValues();
    $output = $pipe($output);

    expect($output->values->get('values'))->toBeArray()
        ->and($output->values->get('values'))->toHaveKey('test')
        ->and($output->values->get('values')['test'])->toBe('2024-04-30');
});
