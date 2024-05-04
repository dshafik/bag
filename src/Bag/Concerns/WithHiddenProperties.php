<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Hidden;
use Bag\Cache;
use Bag\Reflection;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionParameter;
use SensitiveParameter;

trait WithHiddenProperties
{
    protected function getHidden(): LaravelCollection
    {
        return Cache::remember(__METHOD__, $this, function () {
            $hidden = collect();
            collect(Reflection::getConstructor($this)?->getParameters())->each(function (ReflectionParameter $parameter) use (&$hidden) {
                $name = $parameter->getName();
                $isHidden = Reflection::getAttribute($parameter, Hidden::class) !== null || Reflection::getAttribute($parameter, SensitiveParameter::class) !== null;
                if ($isHidden) {
                    $hidden->push($name);
                }
            });

            return $hidden;
        });
    }
}
