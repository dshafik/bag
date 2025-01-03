# Basic Usage

## Creating a Value Object

To create a new Value Object, extend the `Bag\Bag` class and define your properties in the constructor:

```php
use Bag\Bag;

readonly class MyValue extends Bag {
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
```

> [!TIP]
> You can add an `@method` annotation to your class to provide auto-complete for the `::from()` method, or use the [Artisan Command with the --doc option](laravel-artisan-make-bag-command#updating-documentation) to generate it for you.


## Instantiating a Value Object

To create a new instance of your Value Object, call the `::from()` method. You can use an array, a Collection, named arguments, or positional arguments.


### Named Arguments

```php
$value = MyValue::from(
    name: 'Davey Shafik',
    age: => 40,
);
```

### Array or Collection of Arguments

```php
$value = MyValue::from([
    'name' => 'Davey Shafik',
    'age' => 40,
]);
```

or:

```php
$value = MyValue::from(collect([
    'name' => 'Davey Shafik',
    'age' => 40,
]));
```

### Positional Arguments

```php
$value = MyValue::from('Davey Shafik', 40);
```

> [!WARNING]
> If you use positional arguments, you must ensure they are in the same order as the constructor.

> [!WARNING]
> If you have a single array argument, **and** an array [transformer](transformers.md), the transformer will be applied to the array, potentially causing unwanted side-effects.

## Type Casting

Bag will cast all values to their defined type _automatically_ for all scalar types, as well as the following:

- `Bag` objects
- `\Bag\Collection` and `\Illuminate\Support\Collection` objects
- `\DateTimeInterface` objects will be cast using standard [PHP Date/Time formats](https://www.php.net/manual/en/datetime.formats.php)
    - This includes `\DateTime`, `\DateTimeImmutable`, `\Carbon\Carbon` and `\Carbon\CarbonImmutable`
- Enums

> [!TIP]
> We recommend using `\Carbon\CarbonImmutable` for all date times.

### Modifying a Value Object

Value Objects are immutable, so you cannot change their properties directly. Instead, you can create a new instance with the updated values using the `Bag->with()` or `Bag->append()` methods:

```php
$value = MyValue::from([
    'name' => 'Davey Shafik',
    'age' => 40,
]);

$newValue = $value->with(age: 41);

dump($newValue->toArray()); // ['name' => 'Davey Shafik', 'age' => 41] 
```

You can pass either named arguments, or an array or Collection of key-value pairs to the `Bag->with()` method. 

> [!TIP]
> The `Bag->append()` method works the same way as `Bag->with()`, but it will not validate the new value object. You can manually validate the object using `Bag->valid()`.
