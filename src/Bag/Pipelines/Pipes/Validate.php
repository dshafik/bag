<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use BackedEnum;
use Bag\Bag;
use Bag\Internal\Validator as ValidatorAlias;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
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
        $values = $input->values->all();
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

        // Within a Laravel application, we can use the Laravel Validator
        try {
            ValidatorAlias::validate($values, $rules);
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
