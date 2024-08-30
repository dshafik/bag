<?php

declare(strict_types=1);

namespace Bag;

use Bag\Enums\OutputType;
use Bag\Exceptions\ImmutableCollectionException;
use Bag\Pipelines\OutputCollectionPipeline;
use Bag\Pipelines\Values\CollectionOutput;
use Illuminate\Support\Collection as LaravelCollection;
use Override;

class Collection extends LaravelCollection
{
    /**
     * \Illuminate\Contracts\Support\Arrayable<array-key, TValue>|iterable<array-key, TKey>|TKey  $keys
     */
    #[Override]
    public function forget($keys): static
    {
        // @phpstan-ignore-next-line return.type
        return (clone $this)->forgetReal($keys);
    }

    /**
     * \Illuminate\Contracts\Support\Arrayable<array-key, TValue>|iterable<array-key, TKey>|TKey  $keys
     */
    protected function forgetReal($keys): static
    {
        foreach ($this->getArrayableItems($keys) as $key) {
            parent::offsetUnset($key);
        }

        return $this;
    }

    /**
     * @param int $count
     */
    #[Override]
    public function pop($count = 1): mixed
    {
        return (clone $this)->popReal($count);
    }

    protected function popReal(int $count): mixed
    {
        return parent::pop($count);
    }

    /**
     * @param array-key $key
     */
    #[Override]
    public function prepend($value, $key = null): self
    {
        // @phpstan-ignore-next-line return.type
        return (clone $this)->prependReal($value, $key);
    }

    /**
     * @param array-key $key
     */
    protected function prependReal(mixed $value, string|int $key): static
    {
        return parent::prepend($value, $key);
    }

    /**
     * @param array-key $key
     */
    #[Override]
    public function pull($key, $default = null): static
    {
        throw new ImmutableCollectionException('Method pull is not allowed on ' . static::class);
    }

    /**
     */
    #[Override]
    public function push(...$values): static
    {
        // @phpstan-ignore-next-line return.type
        return (clone $this)->pushReal(...$values);
    }

    protected function pushReal(mixed ... $values): static
    {
        return parent::push(...$values);
    }

    /**
     * @param array-key $key
     */
    #[Override]
    public function put($key, $value): static
    {
        // @phpstan-ignore-next-line return.type
        return (clone $this)->putReal($key, $value);
    }

    /**
     * @param array-key $key
     */
    protected function putReal(string|int $key, mixed $value): static
    {
        parent::offsetSet($key, $value);

        return $this;
    }

    /**
     * @param int $count
     */
    #[Override]
    public function shift($count = 1): void
    {
        throw new ImmutableCollectionException('Method shift is not allowed on ' . static::class);
    }

    /**
     * @param int $offset
     * @param int $length
     * @param array $replacement
     * @phpstan-ignore method.childReturnType
     */
    #[Override]
    public function splice($offset, $length = null, $replacement = []): void
    {
        throw new ImmutableCollectionException('Method splice is not allowed on ' . static::class);
    }

    /**
     * @phpstan-ignore method.childReturnType
     */
    #[Override]
    public function transform(callable $callback): void
    {
        throw new ImmutableCollectionException('Method transform is not allowed on ' . static::class);
    }

    /**
     * @param array-key $key
     */
    #[Override]
    public function getOrPut($key, $value): void
    {
        throw new ImmutableCollectionException('Method getOrPut is not allowed on ' . static::class);
    }

    /**
     * @param array-key $key
     */
    #[Override]
    public function offsetSet($key, $value): void
    {
        throw new ImmutableCollectionException('Array key writes not allowed on ' . static::class);
    }

    /**
     * @param array-key $key
     */
    #[Override]
    public function offsetUnset($key): void
    {
        throw new ImmutableCollectionException('Array key writes not allowed on ' . static::class);
    }

    /**
     * @param string $name
     */
    public function __set($name, $value): void
    {
        throw new ImmutableCollectionException('Property writes not allowed on ' . static::class);
    }

    public function toArray(): array
    {
        $output = new CollectionOutput($this, OutputType::ARRAY);

        return OutputCollectionPipeline::process($output)->toArray();
    }

    public function jsonSerialize(): array
    {
        $output = new CollectionOutput($this, OutputType::JSON);

        return OutputCollectionPipeline::process($output)->jsonSerialize();
    }

    public function unwrapped(): array
    {
        $output = new CollectionOutput($this, OutputType::UNWRAPPED);

        return OutputCollectionPipeline::process($output)->toArray();
    }
}
