<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Computed;
use Bag\Bag;
use Bag\Cache;
use Bag\Exceptions\ComputedPropertyUninitializedException;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Bag\Reflection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use ReflectionProperty;

trait WithValidation
{
    public static function rules(): array
    {
        return [];
    }

    public static function validate(LaravelCollection|array $values): bool
    {
        $values = $values instanceof LaravelCollection ? $values->all() : $values;

        $rules = static::getProperties(Reflection::getClass(static::class))->mapWithKeys(function (Value $property) {
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

    protected static function computed(Bag $bag): void
    {
        $computedProperties = Cache::remember(__METHOD__, $bag::class, function () use ($bag) {
            return collect(Reflection::getProperties($bag))->filter(function (ReflectionProperty $property) {
                return Reflection::getAttribute($property, Computed::class) !== null;
            });
        });

        $computedProperties->each(function (ReflectionProperty $property) use ($bag) {
            if ($property->isInitialized($bag)) {
                return;
            }

            throw new ComputedPropertyUninitializedException(sprintf('Property %s->%s must be computed', $bag::class, $property->name));
        });
    }

    abstract protected static function getProperties(ReflectionClass $class): ValueCollection;
}
