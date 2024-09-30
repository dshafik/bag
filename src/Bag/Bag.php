<?php

declare(strict_types=1);

namespace Bag;

use Bag\Concerns\WithArrayable;
use Bag\Concerns\WithCollections;
use Bag\Concerns\WithEloquentCasting;
use Bag\Concerns\WithJson;
use Bag\Concerns\WithOutput;
use Bag\Concerns\WithValidation;
use Bag\Pipelines\InputPipeline;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

readonly class Bag implements Arrayable, Jsonable, JsonSerializable, Castable
{
    use WithArrayable;
    use WithCollections;
    use WithEloquentCasting;
    use WithJson;
    use WithOutput;
    use WithValidation;

    public const FROM_JSON = 'json';

    public static function from(mixed ... $values): static
    {
        $input = new BagInput(static::class, collect($values));

        /** @psalm-var new<static> $bag */
        /** @var static $bag */
        $bag = InputPipeline::process($input);

        return $bag;
    }

    public function with(mixed ...$values): static
    {
        if (count($values) === 1 && isset($values[0])) {
            $values = $values[0];
        }

        $values = \array_merge($this->getRaw()->toArray(), $values);

        return static::from($values);
    }

    public static function rules(): array
    {
        return [];
    }
}
