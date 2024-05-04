<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Wrap;
use Bag\Attributes\WrapJson;
use Bag\Reflection;
use Illuminate\Support\Collection as LaravelCollection;

trait WithWrapping
{
    protected function wrapValues(LaravelCollection $values): LaravelCollection
    {
        $wrapAttribute = Reflection::getAttribute(Reflection::getClass($this), Wrap::class);
        if ($wrapAttribute === null) {
            return $values;
        }

        return ($values::class)::make([Reflection::getAttributeInstance($wrapAttribute)->wrapKey => $values]);
    }

    protected function wrapJsonValues(LaravelCollection $values): LaravelCollection
    {
        $wrapAttribute = Reflection::getAttribute(Reflection::getClass($this), WrapJson::class);
        if ($wrapAttribute === null) {
            $wrapAttribute = Reflection::getAttribute(Reflection::getClass($this), Wrap::class);
            if ($wrapAttribute === null) {
                return $values;
            }
        }

        return ($values::class)::make([Reflection::getAttributeInstance($wrapAttribute)->wrapKey => $values]);
    }
}
