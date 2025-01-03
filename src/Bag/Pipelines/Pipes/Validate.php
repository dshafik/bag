<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use BackedEnum;
use Bag\Bag;
use Bag\Internal\Cache;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\PresenceVerifierInterface;
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
            $key = $aliases['output'][$key] ?? $key;

            return [$key => $rules];
        });

        $rules = $input->params->mapWithKeys(function (Value $property) {
            return [$property->name => $property->validators->all()];
        })->mergeRecursive($rules)->filter();

        if ($rules->isEmpty()) {
            return $input;
        }

        // Within a Laravel application, we can use the Laravel Validator
        try {
            if (class_exists(Validator::class) && method_exists(Validator::class, 'getFacadeRoot')) {
                /** @var object|null $validator */
                $validator = Validator::getFacadeRoot();
                if ($validator !== null && method_exists($validator, 'make')) {
                    $validator = Validator::make($values, $rules->toArray());
                    if ($validator->fails()) {
                        if (method_exists($bagClass, 'redirect')) {
                            /** @var string $redirect */
                            $redirect = app()->call([$bagClass, 'redirect']);
                            $validator->validateWithBag($redirect);
                        }

                        if (method_exists($bagClass, 'redirectRoute')) {
                            /** @var BackedEnum|string $redirectRoute */
                            $redirectRoute = app()->call([$bagClass, 'redirectRoute']);
                            $validator->validateWithBag(route($redirectRoute));
                        }

                        throw new ValidationException($validator);
                    }

                    return $input;
                }
            }
        } catch (BindingResolutionException) {
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

            $validator = new Factory($translator);

            if (class_exists(Application::class) && method_exists($validator, 'setPresenceVerifier')) {
                $app = Application::getInstance();
                if ($app->has('db')) {
                    /** @var PresenceVerifierInterface $presenceVerifier */
                    $presenceVerifier = $app->get('validation.presence');
                    $validator->setPresenceVerifier($presenceVerifier);
                }
            }

            return $validator;
        });

        try {
            $validator->validate($values, $rules->toArray());
        } catch (ValidationException $exception) {
            if (method_exists($bagClass, 'redirect')) {
                /** @var string $redirect */
                $redirect = app()->call([$bagClass, 'redirect']);
                $exception->redirectTo($redirect);
            }

            if (method_exists($bagClass, 'redirectRoute')) {
                /** @var BackedEnum|string $redirectRoute */
                $redirectRoute = app()->call([$bagClass, 'redirectRoute']);
                $exception->redirectTo(route($redirectRoute));
            }

            throw $exception;
        }

        return $input;
    }
}
