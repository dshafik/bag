<?php

declare(strict_types=1);

namespace Bag\Pipelines\Pipes;

use Bag\Bag;
use Bag\Internal\Cache;
use Bag\Pipelines\Values\BagInput;
use Bag\Property\Value;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\ValidationException;

readonly class Validate
{
    public function __invoke(BagInput $input)
    {
        /** @var class-string<Bag> $bagClass */
        $bagClass = $input->bagClassname;
        $values = $input->values->all();
        $aliases = $input->params->aliases();

        $rules = LaravelCollection::wrap($bagClass::rules())->mapWithKeys(function (array $rules, string $key) use ($aliases) {
            $key = $aliases['output'][$key] ?? $key;

            return [$key => $rules];
        });

        $rules = $input->params->mapWithKeys(function (Value $property) {
            return [$property->name => $property->validators->all()];
        })->mergeRecursive($rules)->filter();

        if ($rules->isEmpty()) {
            return $input;
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
            if (method_exists($bagClass, 'redirect')) {
                $exception->redirectTo(app()->call([$bagClass, 'redirect']));
            }

            if (method_exists($bagClass, 'redirectRoute')) {
                $exception->redirectTo(route(app()->call([$bagClass, 'redirectRoute'])));
            }

            throw $exception;
        }

        return $input;
    }
}
