# Upgrading to Bag 2

This guide will help you upgrade your application from Bag 1.x to Bag 2.x.

## Casting with Union Types

To support union types fully, the `\Bag\Casts\CastsPropertySet::set()` method signature has changed the first argument from:

```php
public function set(string $propertyType, string $propertyName, \Illuminate\Support\Collection $properties): mixed
```

to:

```php
public function set(\Bag\Collection $propertyTypes, string $propertyName, \Illuminate\Support\Collection $properties): mixed
```

This change allows for union types to be passed in as a collection of types (as string type names). Your return value must match one of the types in the collection.

## Legacy Behavior

In Bag 1.x the first type in the union was used to cast the property. To update your code and retain the previous behavior, you will want to do the following:

```diff
- public function set(string $propertyType, string $propertyName, \Illuminate\Support\Collection $properties): mixed
- {
+ public function set(\Bag\Collection $propertyTypes, string $propertyName, \Illuminate\Support\Collection $properties): mixed
+ {
+       $propertyType = $propertyTypes->first();
```

## Fill Nullables

The behavior when instantiating a Bag has changed such that arguments that are nullable _without_ a default value are filled with nulls.
Previously, this would have caused exception to be thrown. This solves for a common scenario when you are filling a Bag from user input.

```php
readonly class MyBag extends Bag {
    public function __construct(
         public ?string $name
    }
}

// Bag 1.4.0 (and older)
MyBag::from([]); // throws MissingPropertiesException

// Bag 2.0.0+
MyBag::from([]); // MyBag { $name = null }
```
