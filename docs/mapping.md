# Mapping

Bag allows you to map both input names to properties, and properties to output names. This is useful when
transforming JSON `snake_case` to your codes `camelCase` and vice-versa.

## How Mapping Works

Mapping should be thought of as aliasing property names. The input mapper determines what the _incoming_ alias should be by transforming the original property name, while the output mapper transform the property name and uses it for the _outgoing_ property name. This means
that if you want to map a `propertyName` from the input `property_name` you would use a `SnakeCase` mapper, rather than a `camelCase` mapper.

## Mapping Names

To map names use the `MapName`, `MapInputName`, or `MapOutputName` attributes. The `MapName` attribute can either be applied to the entire class, **or** to an individual property.

### Class-level Mapping

Class-level mapping applies to all properties on the class. This is useful when all properties on a class should be mapped in the same way.

This is done by applying the mapper attribute to the class:

```php
use Bag\Bag;
use Bag\Attributes\MapName;
use Bag\Mappers\SnakeCase;

#[MapName(input: SnakeCase::class, output: SnakeCase::class)]
class MyValue extends Bag {
    public function __construct(
        public string $myValue,
        public string $myOtherValue
    ) {}
}
```

In this above example, the `MyValue` class will map _all_ property names from `snake_case` to `camelCase` on both input and output, in this case, `my_value` to `myValue` and `my_other_value` to `myOtherValue`:

```php
$value = MyValue::from([
    'my_value' => 'value',
    'my_other_value' => 'other value'
]);
```

In addition to the mapped names, you can still use the original property names:

```php
$value = MyValue::from([
    'myValue' => 'value',
    'myOtherValue' => 'other value'
]);
```

You can specify either an `input` or `output`, or both arguments for the `MapName` attribute, or use the `MapInputName` and `MapOutputName` attributes to specify only one.

> [!TIP]
> You can specify a mapper on either the class or property level, or both. If you specify a mapper on both the class and property level, the property-level mapper will take precedence.

> [!WARNING]
> You may only specify _one_ mapper for a given class or property. If you need to apply multiple mappers, you should create a new mapper that combines the desired transformations.

## Built-in Mappers

Bag comes with a few built-in mappers:

- `SnakeCase` - Converts property names to/from `snake_case`
- `CamelCase` - Converts property names to/from `camelCase`
- `Alias` - Allows you to specify a custom alias for a specific property name
- `Stringable` - Converts property names using a sequence of [fluent string helper methods](https://laravel.com/docs/11.x/strings#fluent-strings-method-list).

### Using the Alias Mapper

The `Alias` mapper allows you to specify a custom alias for a specific property name. 

> [!WARNING]
> Unlike other mappers, the `Alias` mapper **must** only be applied to individual properties.

In the following example we alias the input name `uuid` to the property `id`:

```php
use Bag\Bag;
use Bag\Attributes\MapInputName;
use Bag\Mappers\Alias;

class MyValue extends Bag {
    public function __construct(
        #[MapInputName(Alias::class, 'uuid')])]
        public string $id,
    ) {}
}
````

### Using the Stringable Mapper

The `Stringable` mapper allows you to chain any of the [fluent string helper methods](https://laravel.com/docs/11.x/strings#fluent-strings-method-list) to convert property names.

The `Stringable` mapper accepts any number of transformations. To pass in arguments to a given transformation, use a colon `:` followed by a comma-separated list of arguments.

```php
use Bag\Bag;
use Bag\Mappers\SnakeCase;

#[MapName(input: Stringable::class, inputParams: ['camel', 'kebab'], output: \Bag\Mappers\Stringable::class, outputParams: ['camel', 'kebab'])]
class MyValue extends Bag {
    public function __construct(
        public string $myValue,
        public string $myOtherValue
    ) {}
}
```

## When Mapping Applies

Input mapping is applied when calling `Bag::from()`. You can use either the original property name _or_ the mapped name when creating a Bag.

> [!TIP]
> [Validation](validation) is applied to the original property name, not the mapped name.

Output mapping is applied when calling `$Bag->toArray()` or `$Bag->toJson()` (or when using `json_encode()`).

## Custom Mappers

You can also create your own mappers by implementing the `\Bag\Mappers\MapperInterface` interface.

```php
use Bag\Mappers\MapperInterface;
use Illuminate\Support\Str;

class Kebab implements MapperInterface {
    public function input(string $name): string {
        return Str::of($name)->camel()->kebab();
    }

    public function output(string $name): string {
        return Str::of($name)->camel()->kebab();
    }
}
```
