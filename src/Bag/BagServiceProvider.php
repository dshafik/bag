<?php

declare(strict_types=1);

namespace Bag;

use Bag\Console\Commands\MakeBagCommand;
use Bag\Internal\Cache;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class BagServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->beforeResolving(Bag::class, function (string $class, array $parameters, Application $app) {
            if ($this->app->has($class)) {
                return;
            }

            $app->bind(
                $class,
                fn () => Cache::remember('request', $class, function () use ($class): Bag {
                    /** @var Request $request */
                    $request = $this->app->get('request');

                    return $class::from($request->all());
                })
            );
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeBagCommand::class,
            ]);
        }
    }
}
