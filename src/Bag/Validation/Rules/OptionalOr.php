<?php

declare(strict_types=1);

namespace Bag\Validation\Rules;

use Bag\Internal\Validator;
use Bag\Values\Optional as OptionalValue;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class OptionalOr implements ValidationRule
{
    public bool $implicit = true;

    /**
     * @param array<string|ValidationRule>|class-string|null $validation
     */
    public function __construct(protected array|string|null $validation = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->validation === null || $value instanceof OptionalValue) {
            return;
        }

        if (is_string($this->validation) && class_exists($this->validation)) {
            if ($value instanceof $this->validation) {
                return;
            }

            $fail("The :attribute must be an instance of $this->validation.");

            return;
        }

        try {
            /** @var Collection<string, string|ValidationRule> $rules */
            $rules = collect(['value' => Arr::wrap($this->validation)]);
            Validator::validate(['value' => $value], $rules);
        } catch (ValidationException $exception) {
            $fail($exception->errors()['value'][0]);
        }
    }
}
