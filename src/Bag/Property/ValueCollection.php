<?php

declare(strict_types=1);

namespace Bag\Property;

use Illuminate\Support\Collection;

class ValueCollection extends Collection
{
    public function __construct($items = [])
    {
        parent::__construct($items);
    }

    public function required(): static
    {
        return $this->where('required', true);
    }

    public function aliases()
    {
        return collect([
            'input' => $this->mapWithKeys(function (Value $property) {
                return [$property->maps->inputName => $property->name];
            })->toBase(),
            'output' => $this->mapWithKeys(function (Value $property) {
                return [$property->name => $property->maps->outputName];
            })->toBase(),
        ]);
    }
}
