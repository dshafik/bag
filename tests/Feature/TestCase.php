<?php

declare(strict_types=1);

namespace Tests\Feature;

use Bag\BagServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BagServiceProvider::class,
        ];
    }
}
