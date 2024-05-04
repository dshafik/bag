<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\HiddenFromJson;
use Bag\Cache;
use Bag\Collection;
use Illuminate\Support\Collection as LaravelCollection;
use Override;
use ReflectionClass;
use ReflectionParameter;
use SensitiveParameter;

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
        $properties = $this->get();

        return self::prepareOutputValues($properties->except($this->getHidden()->merge($this->getHiddenFromJson())))->toArray();
    }

    protected function getHiddenFromJson(): LaravelCollection
    {
        return Cache::remember(__METHOD__, $this, function () {
            $hidden = collect();

            collect((new ReflectionClass($this))->getConstructor()?->getParameters())->each(function (ReflectionParameter $parameter) use (&$hidden) {
                $name = $parameter->getName();
                $isHidden = ($parameter->getAttributes(HiddenFromJson::class)[0] ?? null) !== null || ($parameter->getAttributes(SensitiveParameter::class)[0] ?? null) !== null;
                if ($isHidden) {
                    $hidden->push($name);
                }
            });

            return $hidden;
        });
    }

    abstract public function get(?string $key = null): mixed;

    abstract protected static function prepareOutputValues(Collection $values): Collection;

    abstract protected function getHidden(): LaravelCollection;
}
