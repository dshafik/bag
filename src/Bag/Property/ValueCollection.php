<?php

declare(strict_types=1);

namespace Bag\Property;

use Illuminate\Support\Collection;

class ValueCollection extends Collection
{
    public function required(): static
    {
        return $this->where('required', true);
    }

    public function aliases(): Collection
    {
        return collect([
            'input' => $this->map(function (Value $property) {
                return $property->maps['input'];
            })->flatMap(function (Collection $aliases, string $name) {
                return $aliases->mapWithKeys(function (string $alias) use ($name) {
                    return [$alias => $name];
                });
            }),
            'output' => $this->mapWithKeys(function (Value $property) {
                return [$property->name => $property->maps['output']];
            })->toBase(),
        ]);
    }
}
