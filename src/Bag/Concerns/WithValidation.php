<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use ReflectionClass;

trait WithValidation
{
    public static function rules(): array
    {
        return [];
    }

    public static function validate(LaravelCollection|array $values): bool
    {
        $values = $values instanceof LaravelCollection ? $values->all() : $values;

        $rules = static::getProperties(new ReflectionClass(static::class))->mapWithKeys(function (Value $property) {
            return [$property->name => $property->validators->all()];
        })->mergeRecursive(static::rules())->filter();

        if ($rules->isEmpty()) {
            return true;
        }

        $validator = Validator::make($values, $rules->toArray());

        try {
            $validator->validate();
        } catch (ValidationException $exception) {
            if (method_exists(static::class, 'redirect')) {
                $exception->redirectTo(app()->call([static::class, 'redirect']));
            }

            if (method_exists(static::class, 'redirectRoute')) {
                $exception->redirectTo(route(app()->call([static::class, 'redirectRoute'])));
            }

            throw $exception;
        }

        return true;
    }

    abstract protected static function getProperties(ReflectionClass $class): ValueCollection;
}
