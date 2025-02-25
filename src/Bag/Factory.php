<?php

declare(strict_types=1);

namespace Bag;

use Faker\Generator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @template T of Bag
 */
abstract class Factory
{
    protected int $count = 1;

    /**
     * @var LaravelCollection<array-key,mixed>
     */
    protected LaravelCollection $state;

    protected Generator $faker;

    /**
     * @var LaravelCollection<array-key, Sequence>
     */
    protected LaravelCollection $sequences;

    /**
     * @param class-string<T> $bagClass
     * @param LaravelCollection<array-key,mixed>|array<array-key,mixed> $data
     */
    public function __construct(
        protected string $bagClass,
        LaravelCollection|array $data = []
    ) {
        $this->faker = \Faker\Factory::create();
        $this->state = LaravelCollection::make($this->definition())->merge($data);
        $this->sequences = LaravelCollection::empty();
    }

    /**
     * @return LaravelCollection<array-key,mixed>|array<array-key,mixed>
     */
    abstract public function definition(): LaravelCollection|array;

    /**
     * @return $this
     */
    public function count(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @param LaravelCollection<array-key,mixed>|array<array-key,mixed>|Sequence $dataOrSequence
     * @return $this
     */
    public function state(LaravelCollection|array|Sequence $dataOrSequence): self
    {
        if ($dataOrSequence instanceof Sequence) {
            $this->sequences->push($dataOrSequence);

            return $this;
        }

        $this->state->merge($dataOrSequence);

        return $this;
    }

    /**
     * @return $this
     */
    public function sequence(mixed ... $sequence): self
    {
        $this->sequences->push(new Sequence(... $sequence));

        return $this;
    }

    /**
     * @param LaravelCollection<array-key,mixed>|array<array-key,mixed> $data
     * @return T|LaravelCollection<array-key,T>|Collection<array-key,T>
     */
    public function make(LaravelCollection|array $data = []): Bag|LaravelCollection|Collection
    {
        $this->state($data);

        $state = clone $this->state;

        /** @var LaravelCollection<array-key,T> $collection */
        $collection = LaravelCollection::empty();

        for ($i = 0; $i < $this->count; $i++) {
            $this->sequences->each(function (Sequence $sequence) use (&$state) {
                /** @var Arrayable<(int|string), mixed>|iterable<(int|string), mixed> $items */
                $items = $sequence();
                $state = $state->merge($items);
            });
            $collection->push($this->bagClass::from($state));
        }

        return ($this->count === 1 ? $collection->first() : $this->bagClass::collect($collection)) ?? Collection::empty();
    }
}
