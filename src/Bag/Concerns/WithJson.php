<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\HiddenFromJson;
use Bag\Collection;
use Illuminate\Support\Collection as LaravelCollection;
use Override;
use ReflectionClass;
use ReflectionParameter;
use SensitiveParameter;
use WeakMap;

trait WithJson
{
    #[Override]
    public function toJson($options = 0): string
    {
        return \json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR | $options);
    }

    #[Override]
    public function jsonSerialize(): mixed
    {
        $properties = $this->getBag();

        return self::prepareOutputValues($properties->except($this->getHidden()->merge($this->getHiddenFromJson())))->toArray();
    }

    protected function getHiddenFromJson(): LaravelCollection
    {
        static $cache = new WeakMap();

        if (isset($cache[$this])) {
            return $cache[$this];
        }

        $hidden = collect();

        collect((new ReflectionClass($this))->getConstructor()?->getParameters())->each(function (ReflectionParameter $parameter) use (&$hidden) {
            $name = $parameter->getName();
            $isHidden = ($parameter->getAttributes(HiddenFromJson::class)[0] ?? null) !== null || ($parameter->getAttributes(SensitiveParameter::class)[0] ?? null) !== null;
            if ($isHidden) {
                $hidden->push($name);
            }
        });

        $cache[$this] = $hidden;

        return $hidden;
    }

    abstract protected function getBag(): Collection;

    abstract protected static function prepareOutputValues(Collection $values): Collection;

    abstract protected function getHidden(): LaravelCollection;
}
