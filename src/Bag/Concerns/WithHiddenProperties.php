<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Hidden;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionClass;
use ReflectionParameter;
use SensitiveParameter;
use WeakMap;

trait WithHiddenProperties
{
    protected function getHidden(): LaravelCollection
    {
        static $cache = new WeakMap();

        if (isset($cache[$this])) {
            return $cache[$this];
        }

        $hidden = collect();

        collect((new ReflectionClass($this))->getConstructor()?->getParameters())->each(function (ReflectionParameter $parameter) use (&$hidden) {
            $name = $parameter->getName();
            $isHidden = ($parameter->getAttributes(Hidden::class)[0] ?? null) !== null || ($parameter->getAttributes(SensitiveParameter::class)[0] ?? null) !== null;
            if ($isHidden) {
                $hidden->push($name);
            }
        });

        $cache[$this] = $hidden;

        return $hidden;
    }
}
