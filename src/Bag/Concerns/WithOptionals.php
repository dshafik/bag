<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Values\Optional;

trait WithOptionals
{
    public function has(string $key): bool
    {
        return (isset($this->{$key}) || $this->{$key} === null) && !($this->{$key} instanceof Optional);
    }

    public function hasAny(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                return true;
            }
        }

        return false;
    }

    public function hasAll(string ...$keys): bool
    {
        foreach ($keys as $key) {
            if (!$this->has($key)) {
                return false;
            }
        }

        return true;
    }
}
