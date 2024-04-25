# Testing

Bag supports Factories to make creating test values easier. Bag factories are similar to [Eloquent factories](https://laravel.com/docs/11.x/eloquent-factories), but they are used to create Bag objects.

To create a factory, extend the `Bag\Factory` class and define the properties you want to set:

```php
use Bag\Factory;
use Illuminate\Support\Collection as LaravelCollection;

class MyValueFactory extends Factory
{

    #[\Override]
    public function definition(): LaravelCollection|array
    {
        return LaravelCollection::make([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);
    }
}
```

Then add the `Bag\Traits\HasFactory` trait and the `Bag\Attributes\Factory` attribute to your Bag class:

```php
use Bag\Bag;
use Bag\Attributes\Factory;
use Bag\Traits\HasFactory;

#[Factory(MyValueFactory::class)]
readonly class MyValue extends Bag {
    use HasFactory;

    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
```

You can now use the `::factory()` method to create Bag objects:

```php
$value = MyValue::factory()->make();
```

This will create a new `MyValue` object using the factory definition.

## Customizing Factory Values

You can also specify custom values when creating a factory, which will override the factory definition. You can pass the values to the `::factory()` call itself,
using the `->state()` method on the factory, or by passing it to the `->make()` method.

```php
// All three are identical:

$value = MyValue::factory([
    'name' => 'Taylor Otwell',
])->make();

$value = MyValue::factory()->make([
    'name' => 'Taylor Otwell',
]);

$value = MyValue::factory()->state([
    'name' => 'Taylor Otwell',
])->make();
```

## Creating Collections of Bag Values

You can use the `->count()` method to create a collection of Bag objects:

```php
$values = MyValue::factory()->count(10)->make();
```

This will create a collection of 10 identical `MyValue` objects.

> [!TIP]
> If your Bag object has a `Collection` attribute, `->make()` will return an instance of that collection class.

## Sequences

Bag factories support Eloquent factory Sequences to generate unique values. You can pass a `Sequence` instance to the `->state()` method, or pass
a closure to the `->sequence()` method.

```php
use Illuminate\Database\Eloquent\Factories\Sequence;

// Both are identical:
$values = MyValue::factory()->count(10)->state(new Sequence(
    fn() => ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)]
))->make();

$values = MyValue::factory()->count(10)->sequence(
    fn() => ['name' => $this->faker->name(), 'age' => $this->faker->numberBetween(18, 100)]
)->make();
```
