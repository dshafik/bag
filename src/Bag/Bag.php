<?php

declare(strict_types=1);

namespace Bag;

use ArrayAccess;
use Bag\Concerns\WithArrayable;
use Bag\Concerns\WithCasts;
use Bag\Concerns\WithCollections;
use Bag\Concerns\WithHiddenProperties;
use Bag\Concerns\WithInput;
use Bag\Concerns\WithJson;
use Bag\Concerns\WithOutput;
use Bag\Concerns\WithProperties;
use Bag\Concerns\WithValidation;
use Bag\Concerns\WithVariadics;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection as LaravelCollection;
use JsonSerializable;

readonly class Bag implements Arrayable, Jsonable, JsonSerializable
{
    use WithArrayable;
    use WithCasts;
    use WithCollections;
    use WithHiddenProperties;
    use WithInput;
    use WithJson;
    use WithOutput;
    use WithProperties;
    use WithValidation;
    use WithVariadics;

    public static function from(ArrayAccess|iterable|Collection|LaravelCollection|Arrayable $values): static
    {
        if (\is_iterable($values)) {
            $values = \iterator_to_array($values);
        }

        $values = self::prepareInputValues(Collection::make($values));

        return new static(...$values->toArray());
    }

    public function with(mixed ...$values): static
    {
        if (count($values) === 1 && isset($values[0])) {
            $values = $values[0];
        }

        $values = \array_merge($this->toArray(), $values);

        return new static(...$values);
    }
}
