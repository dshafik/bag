<?php declare(strict_types=1);

namespace Bag;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Collection as LaravelCollection;

/**
 * @template T of Bag
 */
abstract class Factory
{
    protected int $count = 1;

    protected LaravelCollection $state;

    /**
     * @var LaravelCollection<Sequence>
     */
    protected LaravelCollection $sequences;

    /**
     * @param class-string<T> $bagClass
     */
    public function __construct(
        protected string $bagClass,
        LaravelCollection|array $data = []
    ) {
        $this->state = LaravelCollection::make($this->definition())->merge($data);
        $this->sequences = LaravelCollection::empty();
    }

    abstract public function definition(): LaravelCollection|array;

    public function count(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function state(LaravelCollection|array|Sequence $dataOrSequence): self
    {
        if ($dataOrSequence instanceof Sequence) {
            $this->sequences->push($dataOrSequence);

            return $this;
        }

        $this->state->merge($dataOrSequence);

        return $this;
    }

    public function sequence(mixed ... $sequence): self
    {
        $this->sequences->push(new Sequence(... $sequence));

        return $this;
    }

    /**
     * @return T|LaravelCollection<T>|Collection<T>
     */
    public function make(LaravelCollection|array $data = []): Bag|LaravelCollection|Collection
    {
        $this->state($data);

        $state = clone $this->state;

        $collection = LaravelCollection::empty();

        for ($i = 0; $i < $this->count; $i++) {
            $this->sequences->each(function (Sequence $sequence) use (&$state) {
                $state = $state->merge($sequence());
            });
            $collection->push($this->bagClass::from($state));
        }

        return $this->count === 1 ? $collection->first() : $this->bagClass::collect($collection);
    }
}
