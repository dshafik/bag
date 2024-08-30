<?php

declare(strict_types=1);

namespace Tests;

use Bag\BagServiceProvider;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use function Orchestra\Testbench\workbench_path;
use Tests\Fixtures\Controllers\TestController;

abstract class TestCase extends OrchestraTestCase
{
    protected function prop(object $object, string $property)
    {
        return (fn () => $this->{$property})->call($object);
    }

    public function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }

    public function getPackageProviders($app): array
    {
        return [
            BagServiceProvider::class,
        ];
    }

    public function defineRoutes($router): void
    {
        $router->get('/string/{stringParam}', [TestController::class, 'stringParam'])->middleware(SubstituteBindings::class);
        $router->get('/int/{intParam}', [TestController::class, 'intParam'])->middleware(SubstituteBindings::class);
        $router->get('/model/{modelParam}', [TestController::class, 'modelParam'])->middleware(SubstituteBindings::class);
        $router->get('/invalid/{invalidParam}', [TestController::class, 'invalidParam'])->middleware(SubstituteBindings::class);
        $router->get('/no-binding/{notBound}', [TestController::class, 'noBinding'])->middleware(SubstituteBindings::class);
        $router->post('/with-bag/{stringParam}', [TestController::class, 'withBag'])->middleware(SubstituteBindings::class);
    }
}
