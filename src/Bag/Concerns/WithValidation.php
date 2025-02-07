<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Pipelines\InputPipeline;
use Bag\Pipelines\ValidationPipeline;
use Bag\Pipelines\Values\BagInput;
use Bag\Pipelines\WithoutValidationPipeline;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Validation\ValidationException;

trait WithValidation
{
    /**
     * @param LaravelCollection<array-key,mixed>|array<array-key,mixed> $values
     */
    public static function validate(LaravelCollection|array $values): bool
    {
        $input = new BagInput(static::class, collect($values));

        return ValidationPipeline::process($input);
    }

    public function valid(bool $throwException = true): ?static
    {
        $input = new BagInput(static::class, collect($this->toArray()));

        try {
            $bag = InputPipeline::process($input);
        } catch (ValidationException $e) {
            if ($throwException) {
                throw $e;
            }

            return null;
        }

        return $bag;
    }

    public static function withoutValidation(mixed ... $values): static
    {
        $input = new BagInput(static::class, collect($values));

        return WithoutValidationPipeline::process($input);
    }
}
