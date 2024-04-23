[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=bag&metric=coverage)](https://sonarcloud.io/summary/new_code?id=bag)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=bag&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=bag)

# Bag

Immutable Value Objects for PHP 8.3+ inspired by [spatie/laravel-data](https://spatie.be/docs/laravel-data/v4/introduction).

## Introduction

Bag helps you create immutable value objects. It's a great way to encapsulate data within your application.

Bag prioritizes immutability and type safety with built-in validation and data casting.

### When to use Value Objects 

Value objects should be used in place of regular arrays, allowing you enforce type safety and immutability.

### Laravel Integration

Bag is framework-agnostic, but it works great with Laravel. Bag uses standard Laravel [Collections](https://laravel.com/docs/11.x/collections) and [Validation](https://laravel.com/docs/11.x/validation). In addition, it will automatically inject `Bag\Bag` value objects into your controllers with validation.

## Requirements

Bag requires PHP 8.3+, and supports Laravel 11.x.

## Installation

You can install the package via composer:

```bash
composer require dshafik/bag
```

## Usage

### Creating a Value Object

To create a basic Value Object, extend the `Bag\Bag` class and define your properties in the constructor:

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

### Instantiating a Value Object

To create a new instance of your Value Object, call the `::from()` method:

```php
$value = MyValue::from([
    'name' => 'Davey Shafik',
    'age' => 40,
]);
```

Bag will cast all values to their defined type automatically for all scalar types, as well as the following:

- `Bag` objects
- `\Illuminate\Support\Collection` objects.
- `\DateTimeInterface` objects will be cast using standard [PHP Date/Time formats](https://www.php.net/manual/en/datetime.formats.php)
  - This includes `\DateTime`, `\DateTimeImmutable`, `\Carbon\Carbon` and `\Carbon\CarbonImmutable`.
- Enums

> [!TIP]
> We recommend using `\Carbon\CarbonImmutable` for all date times.

### Modifying a Value Object

Value Objects are immutable, so you cannot change their properties directly. Instead, you can create a new instance with the updated values using the `Bag->with()` method:

```php
$value = MyValue::from([
    'name' => 'Davey Shafik',
    'age' => 40,
]);

$newValue = $value->with(age: 41);

dump($newValue->toArray()); // ['name' => 'Davey Shafik', 'age' => 41] 
```

You can pass either named arguments, or an array or Collection of key-value pairs to the `Bag->with()` method. 

### Collections of Value Objects

You can create a collection of Value objects using the `Bag::collect()` method:

```php
$values = MyValue::collect([
    [
        'name' => 'Davey Shafik',
        'age' => 40,
    ],
    [
        'name' => 'Taylor Otwell',
        'age' => 40,
    ],
]);
```

This will create a `Collection` of `MyValue` objects. If you want to use a custom collection class, you can add the `Collection` attribute to your Bag class:

```php
use App\Values\Collections\MyValueCollection;
use Bag\Bag;
use Bag\Attributes\Collection;

#[Collection(MyValueCollection::class)
readonly class MyValue extends Bag {
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
```

When using `MyValue::collect()` a `MyValueCollection` object will be returned.

### Casting Values

You can explicitly cast values to a specific type using annotations.

Casts can act on both input and output, depending on if they implement `CastsPropertySet` or `CastsPropertyGet`.

Output casts are applied when calling `toArray()`, or when serializing to JSON.

The following annotations are available:

- `Bag\Attributes\Cast` - Cast both input and output depending on whether it implements `CastsPropertySet` and/or `CastsPropertyGet`
- `Bag\Attributes\CastInput` — Only cast input (requires the caster implements `CastsPropertySet`)
- `Bag\Attributes\CastOutput` — Only cast output (requires the caster implements `CastsPropertyGet`)

```php
use Bag\Bag;
use Bag\Attributes\Cast;
use Bag\Casts\DateTime;
use Carbon\CarbonImmutable;

readonly class MyValue extends Bag {
    public function __construct(
        public string $name,
        public int $age,
        #[Cast(DateTime::class, format: 'Y-m-d')]
        public CarbonImmutable $dateOfBirth,
    ) {
    }
}
```

This will cast the `dateOfBirth` property to a `\Carbon\CarbonImmutable` object using the `Y-m-d` format:

```php
$value = MyValue::from([
    'name' => 'Davey Shafik',
    'age' => 40,
    'dateOfBirth' => '1984-05-31',
]);
```

When you access the `dateOfBirth` property directly, it will be a `\Carbon\CarbonImmutable` object:

```php
dump($value->dateOfBirth); // CarbonImmutable
```

However, if you call `toArray()` then it will be formatted using the format you specified:

```php
dump($value->toArray()); // ['name' => 'Davey Shafik', 'age' => 40, 'dateOfBirth' => '1984-05-31']
```

### Validation

Bag uses Laravel's validation system to validate input data. You can define validation rules using annotations, and/or using the `rules()` method.

`Bag` will automatically validate input data when creating a new instance using the `Bag::from()` method.

```php
use Bag\Attributes\Validation\Required;
use Bag\Bag;

readonly class MyValue extends Bag
{
    public function __construct(
        #[Required]
        public string $name,
        public int $age,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['string'],
            'age' => ['integer'],
        ];
    }
}
```

In this example we added a `#[Required]` attribute to the `name` property, and defined validation rules in the `rules()` method.

You can validate a Bag object using the `Bag::validate()` method:

```php
$value = MyValue::validate([
    'name' => 'Davey Shafik',
    'age' => 40,
]);
```

#### Built-in Validation Attributes

Bag provides a number of built-in validation attributes, based on various Laravel validation rules:

| Rule                                                                          |                                                               | Usage                                                   |
|-------------------------------------------------------------------------------|---------------------------------------------------------------|---------------------------------------------------------|
| [Between](https://laravel.com/docs/validation#rule-between)                   | The value should be between two values (inclusive)            | `#[Between(1, 10)]`                                     |
| [Boolean](https://laravel.com/docs/validation#rule-boolean)                   | The value should be a boolean                                 | `#[Boolean]`                                            |
| [Decimal](https://laravel.com/docs/validation#rule-decimal)                   | The value should be a decimal number                          | `#[Decimal]`                                            |
| [Email](https://laravel.com/docs/validation#rule-email)                       | The value should be an email address                          | `#[Email]`                                              |
| [Enum](https://laravel.com/docs/validation#rule-enum)                         | The value should be an enum case                              | `#[Enum(MyEnum::class)]`                                |
| [In](https://laravel.com/docs/validation#rule-in)                             | The value should be in the given list                         | `#[In('foo', 'bar')]`                                   |
| [Integer](https://laravel.com/docs/validation#rule-integer)                   | The value should be an integer                                | `#[Integer]`                                            |
| [Max](https://laravel.com/docs/validation#rule-max)                           | The value should be at most a given size                      | `#[Max(100)]`                                           |
| [Min](https://laravel.com/docs/validation#rule-min)                           | The value should be at minimum a given size                   | `#[Min(1)]`                                             |
| [NotRegex](https://laravel.com/docs/validation#rule-not-regex)                | The value should not match a given regex                      | `#[NotRegex('/regex/')]`                                |
| [Numeric](https://laravel.com/docs/validation#rule-numeric)                   | The value should be numeric                                   | `#[Numeric]`                                            |
| [Regex](https://laravel.com/docs/validation#rule-regex)                       | The value should match a given regex                          | `#[Regex('/regex/')]`                                   |
| [Required](https://laravel.com/docs/validation#rule-required)                 | The value is required                                         | `#[Required]`                                           |
| [RequiredIf](https://laravel.com/docs/validation#rule-required-if)            | The value is required if another field matches a value        | `#[RequiredIf('otherField', 'value')]`                  |
| [RequiredUnless](https://laravel.com/docs/validation#rule-required-unless)    | The value is required unless another field matches a value    | `#[RequiredUnless('otherField', 'value')]`              |
| [RequiredWith](https://laravel.com/docs/validation#rule-required-with)        | The value is required if another field is present             | `#[RequiredWith('otherField')]`                         |
| [RequiredWithAll](https://laravel.com/docs/validation#rule-required-with-all) | The value is required if more than one other field is present | `#[RequiredWithAll('otherField', 'anotherOtherField')]` |
| [Size](https://laravel.com/docs/validation#rule-size)                         | The value should have a specific size                         | `#[Size(10)]`                                           |
| [Str](https://laravel.com/docs/validation#rule-string)                        | The value should be a string                                  | `#[Str]`                                                |

In addition, a generic `\Bag\Attributes\Validation\Rule` attribute is available to apply any Laravel validation rule:

```php
use Bag\Attributes\Validation\Rule;

readonly class MyValue extends Bag
{
    public function __construct(
        #[Required]
        public string $username,
        public string $password,
        #[Rule('same:password')]
        public string $passwordConfirmation,
    ) {
    }
}
```

### Controller Injection

Bag can automatically inject validated Bag objects into your controllers using Laravel's automatic dependency injection.

```php
use App\Values\MyValue;

class MyController extends Controller {
    public function store(MyValue $value) {
        // $value is a validated MyValue object
    }
}
```

When you type hint a Bag object in your controller method, Bag will automatically validate the request data and inject the Bag object into your controller method.

## Testing

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

### Specifying Properties

You can also specify values when creating a factory, which will override the factory definition. You can pass the values to the `::factory()` call itself, 
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

### Creating Collections of Bag Values

You can use the `->count()` method to create a collection of Bag objects:

```php
$values = MyValue::factory()->count(10)->make();
```

This will create a collection of 10 identical `MyValue` objects.

> [!TIP]
> If your Bag object has a `Collection` attribute, `->make()` will return an instance of that collection class.

### Sequences

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

## Roadmap

The following features are planned:

- [ ] Support for artisan commands to make Bag objects, collections, and factories
