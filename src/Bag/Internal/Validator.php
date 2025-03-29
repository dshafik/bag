<?php

declare(strict_types=1);

namespace Bag\Internal;

use Bag\Exceptions\ValidatorNotFoundException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Illuminate\Validation\PresenceVerifierInterface;

class Validator
{
    /**
     * @param array<string, mixed> $values
     * @param Collection<string, string|ValidationRule> $rules
     */
    public static function validate(array $values, Collection $rules): void
    {
        try {
            if (class_exists(Validator::class)) {
                /** @var object|null $validator */
                $validator = \Illuminate\Support\Facades\Validator::getFacadeRoot();
                if ($validator !== null && method_exists($validator, 'make')) {
                    $validator = \Illuminate\Support\Facades\Validator::make($values, $rules->toArray());
                    $validator->validate();
                } else {
                    throw new ValidatorNotFoundException();
                }
            }
        } catch (BindingResolutionException|ValidatorNotFoundException) {
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

                if (class_exists(Application::class)) {
                    $app = Application::getInstance();
                    if ($app->has('db')) {
                        /** @var PresenceVerifierInterface $presenceVerifier */
                        $presenceVerifier = $app->get('validation.presence');
                        $validator->setPresenceVerifier($presenceVerifier);
                    }
                }

                return $validator;
            });

            $validator->validate($values, $rules->toArray());
        }
    }
}
