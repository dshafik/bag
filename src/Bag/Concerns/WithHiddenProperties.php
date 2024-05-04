<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Hidden;
use Bag\Cache;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionClass;
use ReflectionParameter;
use SensitiveParameter;

trait WithHiddenProperties
{
    protected function getHidden(): LaravelCollection
    {
        return Cache::remember(__METHOD__, $this, function () {
            $hidden = collect();
            collect((new ReflectionClass($this))->getConstructor()?->getParameters())->each(function (ReflectionParameter $parameter) use (&$hidden) {
                $name = $parameter->getName();
                $isHidden = ($parameter->getAttributes(Hidden::class)[0] ?? null) !== null || ($parameter->getAttributes(SensitiveParameter::class)[0] ?? null) !== null;
                if ($isHidden) {
                    $hidden->push($name);
                }
            });

            return $hidden;
        });
    }
}
