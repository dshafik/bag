# Testing

Bag supports Factories to make creating test values easier. Bag factories are similar to [Eloquent factories](https://laravel.com/docs/11.x/eloquent-factories), but they are used to create Bag objects.

## Creating a Factory

Factories extend the `Bag\Factory` class, and define a `definition()` method that returns an array of default values for the value object.

```php
use Bag\Factory;

class MyValueFactory extends Factory {
    #[Override]
    public function definition(): array {
        return [
            'name' => 'Davey Shafik',
            'age' => 40,
        ];
    }
}
```

### Faker Integration

Factories include [Faker](https://fakerphp.org) support out of the box. You can use the `$faker` property to generate random values:

```php
return [
    'name' => $this->faker->name(),
    'age' => $this->faker->numberBetween(18, 65),
];
```

> [!TIP]
> You can also generate factory classes automatically using the [`artisan make:bag`](./laravel-artisan-make-bag-command) command.

## Using a Factory

Before you can use a Factory, you must first add both the `Factory` attribute and the `HasFactory` trait to your Bag object:

```php{5,7}
use Bag\Attributes\Factory;
use Bag\Bag;
use Bag\Traits\HasFactory;

#[Factory(MyValueFactory::class)]
class MyValue extends Bag {
    use HasFactory;
    
    public function __construct(
        public string $name,
        public int $age,
    ) {}
}
```

You can now use the factory to create a new instance of the value object:

```php
$bag = MyValue::factory()->make();
```

This will create a new `MyValue` object using the factory definition.

## Customizing Factory State

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

## Named States

Bag supports named states, which allow you to modify the state of the value object when creating it:

```php
use Bag\Factory;

class MyValueFactory extends Factory {
    public function definition(): array {
        return [
            'name' => 'Davey Shafik',
            'age' => 40,
        ];
    }

    public function withName(string $name): static {
        return $this->state([
            'name' => $name,
        ]);
    }
}
```

You can now use the state when creating the value object:

```php
$bag = MyValue::factory()->withName($faker->name())->make();
```

## Creating Collections of Bag Values

You can use the `->count()` method to create a collection of Bag objects:

```php
$values = MyValue::factory()->count(10)->make();
```

This will create a `Bag\Collection` of 10 identical `MyValue` objects.

> [!TIP]
> If your Bag object has a `Collection` attribute, `->make()` will return an instance of that collection class.

## Sequences

Bag factories support Eloquent factory Sequences to generate unique values for each instance in a collection.

```php
use Illuminate\Database\Eloquent\Factories\Sequence;

$bag = MyValue::factory()->count(10)->sequence(fn(Sequence $sequence) => [
    'name' => 'Person #' . $sequence->index,
    'age' => 18 + $sequence->index,
])->make();
```

In this example, the `name` property will be set to `Person #1`, `Person #2`, etc., and the `age` property will be set to `19`, `20`, etc.

The `->sequence()` method accepts any of the following:

- A `Illuminate\Database\Eloquent\Factories\Sequence` instance created with a `closure` that returns an array of values
- A `Illuminate\Database\Eloquent\Factories\Sequence` instance created with a variadic number of arrays of values
- A `closure` value that returns an array of values
- A variadic number of arrays of values

You may also pass a `Sequence` object to the `->state()` method.

> [!TIP]
> If you create more values than number of value arrays passed in, the sequence will start over from the beginning.

> [!WARNING]
> If you use both states (named or via the `::factory()`, `->state()`, or `->make()` methods) and sequences, sequences will be applied _after_ the state, so the sequences will override any values set by the state.

