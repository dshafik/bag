<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use BackedEnum;
use Bag\Bag;
use Bag\Internal\Validator;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Bag\Validation\Rules\OptionalOr;
use Bag\Values\Optional;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Validation\ValidationException;

readonly class Validate
{
    /**
     * @template T of Bag
     * @param BagInput<T> $input
     * @return BagInput<T>
     */
    public function __invoke(BagInput $input): BagInput
    {
        /** @var class-string<Bag> $bagClass */
        $bagClass = $input->bagClassname;
        $aliases = $input->params->aliases();

        $rules = LaravelCollection::wrap($bagClass::rules())->mapWithKeys(function (mixed $rules, string $key) use ($aliases) {
            $key = $aliases['input'][$key] ?? $key;

            return [$key => $rules];
        });

        /** @var LaravelCollection<string, string|ValidationRule> $rules */
        $rules = $input->params->mapWithKeys(function (Value $property) {
            return [$property->name => $property->validators->all()];
        })->toBase()->mergeRecursive($rules)->filter();

        if ($rules->isEmpty()) {
            return $input;
        }

        $values = $input->values->filter(function ($value, $key) use ($rules) {
            if (!$value instanceof Optional) {
                return true;
            }

            $fieldRules = $rules->get($key, []);
            if (!is_array($fieldRules)) {
                $fieldRules = [$fieldRules];
            }

            foreach ($fieldRules as $rule) {
                if ($rule instanceof OptionalOr) {
                    return true;
                }
            }

            // Optional value without OptionalOr validator should be removed
            return false;
        });

        // Within a Laravel application, we can use the Laravel Validator
        try {
            Validator::validate($values->all(), $rules);
        } catch (ValidationException $exception) {
            if (method_exists($bagClass, 'redirect')) {
                /** @var string $redirect */
                // @phpstan-ignore argument.type
                $redirect = app()->call([$bagClass, 'redirect']);
                $exception->redirectTo($redirect);
            }

            if (method_exists($bagClass, 'redirectRoute')) {
                /** @var BackedEnum|string $redirectRoute */
                // @phpstan-ignore argument.type
                $redirectRoute = app()->call([$bagClass, 'redirectRoute']);
                $exception->redirectTo(route($redirectRoute));
            }

            throw $exception;
        }

        return $input;
    }

}
