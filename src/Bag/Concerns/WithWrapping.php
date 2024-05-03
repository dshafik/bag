<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Wrap;
use Bag\Attributes\WrapJson;
use Bag\Cache;
use Illuminate\Support\Collection as LaravelCollection;
use ReflectionClass;

trait WithWrapping
{
    protected function wrapValues(LaravelCollection $values): LaravelCollection
    {
        $wrapAttributes = Cache::remember('wrapValues', $this, fn () => (new ReflectionClass($this))->getAttributes(Wrap::class));
        if (count($wrapAttributes) === 0) {
            return $values;
        }

        return ($values::class)::make([$wrapAttributes[0]->newInstance()->wrapKey => $values]);
    }

    protected function wrapJsonValues(LaravelCollection $values): LaravelCollection
    {
        $wrapAttributes = Cache::remember('wrapValuesJson', $this, fn () => (new ReflectionClass($this))->getAttributes(WrapJson::class));
        if (count($wrapAttributes) === 0) {
            $wrapAttributes = Cache::remember('wrapValues', $this, fn () => (new ReflectionClass($this))->getAttributes(Wrap::class));
            if (count($wrapAttributes) === 0) {
                return $values;
            }
        }

        return ($values::class)::make([$wrapAttributes[0]->newInstance()->wrapKey => $values]);
    }
}
