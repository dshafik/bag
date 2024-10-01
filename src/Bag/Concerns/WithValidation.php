<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Pipelines\ValidationPipeline;
use Bag\Pipelines\Values\BagInput;
use Illuminate\Support\Collection as LaravelCollection;

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
}
