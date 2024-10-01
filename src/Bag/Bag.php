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
use Illuminate\Contracts\Validation\ValidationRule;
use JsonSerializable;

/**
 * @implements Arrayable<array-key, mixed>
 */
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

        return InputPipeline::process($input);
    }

    public function with(mixed ...$values): static
    {
        if (count($values) === 1 && isset($values[0])) {
            $values = $values[0];
        }

        $values = \array_merge($this->getRaw()->toArray(), (array) $values);

        return static::from($values);
    }

    /**
     * @return array<array-key, string|ValidationRule|class-string<ValidationRule>>
     */
    public static function rules(): array
    {
        return [];
    }
}
