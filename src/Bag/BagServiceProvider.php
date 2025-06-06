<?php

declare(strict_types=1);

namespace Bag;

use Bag\Attributes\StripExtraParameters;
use Bag\Attributes\WithoutValidation;
use Bag\Console\Commands\MakeBagCommand;
use Bag\DebugBar\Collectors\BagCollector;
use Bag\Internal\Cache;
use Bag\Internal\Reflection;
use Barryvdh\Debugbar\LaravelDebugbar;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\SerializableClosure\Support\ReflectionClosure;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionParameter;

class BagServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->beforeResolving(Bag::class, function (string $class, array $parameters, Application $app) {
            if ($this->app->has($class)) {
                return;
            }

            /** @var class-string<Bag> $class */

            $app->bind(
                $class,
                fn () => Cache::remember('request', $class, function () use ($class): Bag {
                    /** @var Request $request */
                    $request = $this->app->get('request');

                    $values = collect($request->all())->filter();

                    if (($route = $request->route()) !== null) {
                        if (($controller = $route->getController()) === null) {
                            /** @var Closure $closure */
                            $closure = $route->getAction('uses');
                            $action = new ReflectionClosure($closure);
                        } else {
                            /** @var object|string $controller */
                            $action = new ReflectionMethod($controller, $route->getActionMethod());
                        }

                        if (Reflection::getParameters($action)->filter(function ($parameter) use ($class) {
                            /** @var ReflectionParameter $parameter */
                            /** @var ReflectionNamedType|null $type */
                            $type = $parameter->getType();
                            if ($type?->getName() === $class) {
                                return Reflection::getAttribute($parameter, WithoutValidation::class) !== null;
                            }

                            return false;
                        })->isNotEmpty()) {
                            return $class::withoutValidation($values);
                        }

                        if (Reflection::getParameters($action)->filter(function ($parameter) use ($class) {
                            /** @var ReflectionParameter $parameter */
                            /** @var ReflectionNamedType|null $type */
                            $type = $parameter->getType();
                            if ($type?->getName() === $class) {
                                return Reflection::getAttribute($parameter, StripExtraParameters::class) !== null;
                            }

                            return false;
                        })->isNotEmpty()) {
                            return $class::from($class::withoutValidation($values)->getRaw());
                        }
                    }


                    return $class::from($values);
                })
            );
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeBagCommand::class,
            ]);
        }

        if (class_exists(LaravelDebugbar::class)) {
            if ($this->app->bound(LaravelDebugbar::class)) {
                BagCollector::init();
                // @phpstan-ignore argument.type
                $this->app->make(LaravelDebugbar::class)->addCollector(new BagCollector());
            }
        }
    }
}
