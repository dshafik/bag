<?php

declare(strict_types=1);

namespace Bag;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class BagServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->beforeResolving(Bag::class, function ($class, $parameters, $app) {
            if ($app->has($class)) {
                return;
            }

            $app->bind(
                $class,
                function (Application $container) use ($class) {
                    return $class::from($container['request']);
                }
            );
        });
    }
}
