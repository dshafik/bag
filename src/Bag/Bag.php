<?php

declare(strict_types=1);

namespace Bag;

use Bag\Concerns\WithArrayable;
use Bag\Concerns\WithCollections;
use Bag\Concerns\WithEloquentCasting;
use Bag\Concerns\WithJson;
use Bag\Concerns\WithOptionals;
use Bag\Concerns\WithOutput;
use Bag\Concerns\WithValidation;
use Bag\Pipelines\InputPipeline;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
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
    use WithOptionals;
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

    public function append(mixed ...$values): static
    {
        if (count($values) === 1 && isset($values[0])) {
            $values = $values[0];
        }

        $values = \array_merge($this->getRaw()->toArray(), (array) $values);

        return static::withoutValidation($values);
    }

    /**
     * @return array<array-key, string|ValidationRule|Rule|DataAwareRule|ValidatorAwareRule|class-string<ValidationRule|Rule|DataAwareRule|ValidatorAwareRule>|array<string|ValidationRule|Rule|DataAwareRule|ValidatorAwareRule|class-string<ValidationRule|Rule|DataAwareRule|ValidatorAwareRule>>>
     */
    public static function rules(): array
    {
        return [];
    }

    /**
     * @param array<mixed> $array
     */
    public static function __set_state(array $array): static
    {
        return static::from($array);
    }
}
