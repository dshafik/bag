<?php

declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function prop(object $object, string $property)
    {
        return (fn () => $this->{$property})->call($object);
    }
}
