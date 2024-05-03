<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Collection;
use Illuminate\Support\Collection as LaravelCollection;
use Override;

trait WithArrayable
{
    #[Override]
    public function toArray(): array
    {
        $properties = $this->get();

        return $this->wrapValues(self::prepareOutputValues($properties->except($this->getHidden())))->toArray();
    }

    abstract public function get(?string $key = null): mixed;

    abstract protected static function prepareOutputValues(Collection $values): Collection;

    abstract protected function getHidden(): LaravelCollection;

    abstract protected function wrapValues(LaravelCollection $values): LaravelCollection;
}
