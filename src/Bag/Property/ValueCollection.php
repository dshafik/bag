<?php

declare(strict_types=1);

namespace Bag\Property;

use Illuminate\Support\Collection;

/**
 * @extends Collection<string, Value>
 */
class ValueCollection extends Collection
{
    public function required(): static
    {
        return $this->where('required', true);
    }

    public function nullable(): static
    {
        return $this->where('allowsNull', true);
    }

    /**
     * @return Collection<string, Collection<string, string>>
     */
    public function aliases(): Collection
    {
        /** @var Collection<string, Collection<string, string>> $aliases */
        $aliases = collect([
            'input' => $this->toBase()->map(function (Value $property) {
                return $property->maps['input'];
            })->flatMap(function (mixed $aliases, string $name) {
                /** @var Collection<array-key, string> $aliases */
                return $aliases->mapWithKeys(function (mixed $alias) use ($name) {
                    /** @var string $alias */
                    return [$alias => $name];
                });
            }),
            'output' => $this->mapWithKeys(function (Value $property) {
                return [$property->name => $property->maps['output']];
            })->toBase(),
        ]);

        return $aliases;
    }
}
