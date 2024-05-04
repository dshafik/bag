<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Cache;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
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

        $validator = Cache::remember(__METHOD__, 'validator', function () {
            $filesystem = new Filesystem();
            $loader = new FileLoader($filesystem, [
                __DIR__ . '/../../../vendor/laravel/framework/src/Illuminate/Translation/lang',
                __DIR__ . '/../../../../vendor/laravel/framework/src/Illuminate/Translation/lang',
                __DIR__ . '/../../../../../vendor/laravel/framework/src/Illuminate/Translation/lang',
                __DIR__ . '/../../../../../../vendor/laravel/framework/src/Illuminate/Translation/lang',
            ]);
            $translator = new Translator($loader, 'en');

            return new Factory($translator);
        });

        try {
            $validator->validate($values, $rules->toArray());
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
