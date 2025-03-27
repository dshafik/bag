<?php

declare(strict_types=1);

use Bag\DebugBar\Collectors\BagCollector;
use Bag\Pipelines\Pipes\DebugCollection;
use Bag\Pipelines\Values\BagInput;
use DebugBar\DataCollector\MessagesCollector;
use Illuminate\Support\Collection;
use Tests\Fixtures\Values\TestBag;

covers(DebugCollection::class);

beforeEach()->skip(!class_exists(MessagesCollector::class));

test('it collects bags', function () {
    $pipe = new DebugCollection();
    $input = new BagInput(TestBag::class, Collection::empty());
    $bag = new TestBag('davey@php.net', 40, 'davey@php.net');
    $input->bag = $bag;

    $result = $pipe($input);

    $bags = (new class () extends BagCollector {
        public function getBags()
        {
            return static::$bags;
        }
    })->getBags();

    $key = TestBag::class . '#' . spl_object_id($bag);

    expect($result)
        ->toBe($input)
    ->and($bags)
        ->toHaveKey($key)
    ->and($bags[$key])
        ->toHaveKeys(['bag', 'location', 'type']);
});
