<?php

declare(strict_types=1);

namespace Bag;

use ArrayAccess;
use Bag\Attributes\Hidden;
use Bag\Attributes\HiddenFromJson;
use Bag\Attributes\Variadic;
use Bag\Exceptions\AdditionalPropertiesException;
use Bag\Exceptions\MissingPropertiesException;
use Bag\Property\Value;
use Bag\Property\ValueCollection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection as LaravelCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use JsonSerializable;
use Override;
use ReflectionClass;
use ReflectionParameter;
use RuntimeException;
use SensitiveParameter;
use WeakMap;

readonly class Bag implements Arrayable, Jsonable, JsonSerializable
{
    public static function rules(): array
    {
        return [];
    }

    public static function from(ArrayAccess|iterable|Collection|LaravelCollection|Arrayable $values): static
    {
        if (\is_iterable($values)) {
            $values = \iterator_to_array($values);
        }

        $values = self::prepareInputValues(Collection::make($values));

        return new static(...$values->toArray());
    }

    public function with(mixed ...$values): static
    {
        if (count($values) === 1 && isset($values[0])) {
            $values = $values[0];
        }

        $values = \array_merge($this->toArray(), $values);

        return new static(...$values);
    }

    /**
     * @param  iterable<int, mixed>  $values
     * @return Collection<static>
     */
    public static function collect(iterable $values = []): Collection
    {
        static $cache = [];

        if (isset($cache[static::class])) {
            return $cache[static::class]::make($values)->map(fn ($value): static => static::from($value));
        }

        $cache[static::class] = Collection::class;

        $collectionAttributes = (new ReflectionClass(static::class))->getAttributes(Attributes\Collection::class);
        if (count($collectionAttributes) > 0) {
            $cache[static::class] = $collectionAttributes[0]->newInstance()->collectionClass;
        }

        return $cache[static::class]::make($values)->map(fn ($value): static => static::from($value));
    }

    #[Override]
    public function toArray(): array
    {
        $properties = $this->getBag();

        return self::prepareOutputValues($properties->except($this->getHidden()))->toArray();
    }

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

    public static function validate(LaravelCollection|array $values): bool
    {
        $values = $values instanceof LaravelCollection ? $values->toArray() : $values;

        $rules = static::getProperties(new ReflectionClass(static::class))->map(function (Value $property) {
            return $property->validators;
        })->flatten()->merge(static::rules());

        if ($rules->isEmpty()) {
            return true;
        }

        $validator = Validator::make($values, $rules->toArray());

        try {
            $validator->validate();
        } catch (ValidationException $exception) {
            if (method_exists(static::class, 'redirect')) {
                $exception->redirectTo(app()->call([static::class, 'redirect']));
            }

            if (method_exists(static::class, 'redirectRoute')) {
                $exception->redirectTo(route(app()->call([static::class, 'redirectRoute'])));
            }

            throw $exception;
        }

        return true;
    }

    protected static function prepareInputValues(Collection $values): Collection
    {
        $class = new ReflectionClass(static::class);
        $properties = self::getProperties($class);

        $requiredProperties = $properties->required();
        $aliases = $properties->aliases();
        $extraProperties = collect();

        $variadics = $class->getAttributes(Variadic::class);
        /** @var Variadic $variadic */
        $variadic = isset($variadics[0]) ? $variadics[0]->newInstance() : null;

        $isVariadic = false;
        if ($variadic !== null || $properties->last()->variadic) {
            $isVariadic = true;
        }

        $values = $values->mapWithKeys(function ($value, $inputKey) use ($variadic, &$properties, &$requiredProperties, &$extraProperties, &$aliases) {
            $key = $aliases['input'][$inputKey] ?? $inputKey;

            if (! isset($properties[$key])) {
                $extraProperties[$key] = true;

                if ($variadic !== null) {
                    /** @var Value $last */
                    $last = $properties->last();
                    if ($last->variadic) {
                        $propertyType = $last->type;
                    } else {
                        $propertyType = \gettype($value);
                    }

                    $variadic->cast($propertyType, $key, $properties);
                }

                return [$key => $value];
            }

            if (isset($requiredProperties[$key])) {
                unset($requiredProperties[$key]);
            }

            return [$key => $value];
        });

        static::validate($values);

        $values = $values->map(function ($value, $inputKey) use ($values, $aliases, $properties, $extraProperties) {
            $key = $aliases['input'][$inputKey] ?? $inputKey;

            if (isset($extraProperties[$key])) {
                return $value;
            }

            /** @var Value $property */
            $property = $properties[$key];

            return ($property->inputCast)($key, $values);
        });

        $requiredProperties->whenNotEmpty(fn () => throw new MissingPropertiesException($requiredProperties));
        if (! $isVariadic) {
            $extraProperties->whenNotEmpty(fn () => throw new AdditionalPropertiesException($extraProperties));
        }

        return $values;
    }

    protected static function prepareOutputValues(Collection $values): Collection
    {
        $properties = self::getProperties(new ReflectionClass(static::class));

        $aliases = $properties->aliases();

        return $values->mapWithKeys(function (mixed $_, string $key) use ($properties, $aliases, $values) {
            /** @var Value $property */
            $property = $properties[$key];

            if (isset($aliases['output'][$key])) {
                $key = $aliases['output'][$key];
            }

            $value = ($property->outputCast)($values);

            return [$key => $value];
        });
    }

    protected static function getProperties(ReflectionClass $class): ValueCollection
    {
        static $cache = [];
        $key = static::class;
        if (isset($cache[$key])) {
            return $cache[$key];
        }

        /** @var ValueCollection $properties */
        $properties = ValueCollection::make($class->getConstructor()?->getParameters())->mapWithKeys(function (ReflectionParameter $property) use ($class) {
            return [$property->getName() => Value::create($class, $property)];
        });

        if ($properties === null || $properties->count() === 0) {
            throw new RuntimeException(sprintf('Bag "%s" must have a constructor with at least one property', static::class));
        }

        $cache[$key] = $properties;

        return $properties;
    }

    protected function getBag(): Collection
    {
        $value = $this;

        return Collection::make((fn (): array => \get_object_vars($value))->bindTo(null)());
    }

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
}
