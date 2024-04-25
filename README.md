<p align="center">
  <img src="https://dshafik.github.io/bag/assets/images/social.png" width="100%" alt="Bag" width="200">
</p>
<p align="center">
  <a href="https://sonarcloud.io/summary/new_code?id=bag">
      <img src="https://sonarcloud.io/api/project_badges/measure?project=bag&metric=coverage" alt="Coverage">
  </a>
  <a href="https://sonarcloud.io/summary/new_code?id=bag">
      <img src="https://sonarcloud.io/api/project_badges/measure?project=bag&metric=alert_status" alt="Quality Gate Status">
  </a>
</p>

# Bag

Immutable Value Objects for PHP 8.3+ inspired by [spatie/laravel-data](https://spatie.be/docs/laravel-data/v4/introduction), created by [Davey Shafik](https://www.daveyshafik.com).

## Introduction

Bag helps you create immutable value objects. It's a great way to encapsulate data within your application.

Bag prioritizes immutability and type safety with built-in validation and data casting.

### When to use Value Objects 

Value objects should be used in place of regular arrays, allowing you enforce type safety and immutability.

### Features

- Immutable & Strongly typed
- Value casting — both input and output
- Collection support
- Composable — nest Bag value objects and collections
- Built-in validation

> [!NOTE]
> Bag is framework-agnostic, but it works great with Laravel. Bag uses standard Laravel [Collections](https://laravel.com/docs/11.x/collections) and [Validation](https://laravel.com/docs/11.x/validation). In addition, it will automatically inject `Bag\Bag` value objects into your controllers with validation.

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

## Documentation

Full documentation can be found [here](https://dshafik.github.io/bag).
