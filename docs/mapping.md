# Mapping

Bag allows you to map both input names to properties, and properties to output names. This is useful when
transforming JSON `snake_case` to your codes `camelCase` and vice-versa.

## How Mapping Works

Mapping should be thought of as aliasing property names. The input mapper determines what the _incoming_ aliases _could_ be by transforming the original property name, while the output mapper transform the property name and uses it for the _outgoing_ property name. This means
that if you want to map a `propertyName` from the input `property_name` you would use a `SnakeCase` mapper, rather than a `camelCase` mapper.

## Mapping Names

To map names use the `Bag\Attributes\MapName`, `Bag\Attributes\MapInputName`, or `Bag\Attributes\MapOutputName` attributes. These attributes can either be applied to the entire class, **or** to an individual property.

### Class-level Mapping

Class-level mapping applies to all properties on the class. This is useful when all properties on a class should be mapped in the same way.

This is done by applying the mapper attribute to the class:

```php{5,6}
use Bag\Bag;
use Bag\Attributes\MapName;
use Bag\Mappers\SnakeCase;

#[MapInputName(SnakeCase::class)
#[MapOutputName(SnakeCase::class)]
class MyValue extends Bag {
    public function __construct(
        public string $myValue,
        public string $myOtherValue
    ) {}
}
```

> [!NOTE]
> The above is functionally equivelent to using:
> ```php
> #[MapName(input: SnakeCase::class, output: SnakeCase::class)]
> ```
> 
> We recommend using the `MapInputName` and `MapOutputName` attributes as mapper arguments can be passed 
> directly, rather than as an array of values to either the `inputParams` and/or `outputParams` arguments:
> 
> ```php
> #[MapInputName(MapperName::class, 'param1', 'param2')]
> #[MapOutputName(MapperName::class, 'param1', 'param2')]
> 
> // vs
> 
> #[MapName(input: MapperName::class, inputParams: ['param1', 'param2'], output: MapperName::class, outputParams: ['param1', 'param2'])]
> ```

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

> [!TIP]
> You can specify a mapper on either the class or property level, or both. If you specify a mapper on both the class and property level, the property-level mapper will take precedence.

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

```php{7}
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

```php{4,5}
use Bag\Bag;
use Bag\Mappers\Stringable;

#[MapInputName(Stringable::class, 'camel', 'kebab')]
#[MapOutputName(Stringable::class, 'camel', 'kebab')]
class MyValue extends Bag {
    public function __construct(
        public string $myValue,
        public string $myOtherValue
    ) {}
}
```

## When Mapping Applies

Input mapping is applied when calling `Bag::from()` or `Bag::withoutValidation()`. You can use either the original property name _or_ the mapped name when creating a Bag.

> [!TIP]
> [Validation](validation) is applied to the original property name, not the mapped name.

Output mapping is applied when calling `$Bag->toArray()` or `$Bag->toJson()` (or when using `json_encode()`).

## Mapping Hierarchy

For input mapping, _all_ mappers are used, allowing _multiple_ mapped names to match to the same property. **The last _incoming_
property name that matches will be the value used for the Bag.**

The mapping hierarchy is as follows:

- Class Level: `MapName(input)`
  - Class Level: `MapInputName`
    - Property Level: `MapName(input)`
      - Property Level: `MapInputName`

For output mapping, _only_ the last mapper is used. The mapping hierarchy is as follows:

- Class Level: `MapName(output)`
  - Class Level: `MapOutputName`
    - PropertyLevel Level: `MapName(Output)`
      - PropertyLevel Level: `MapOutputName`

> [!NOTE]
> The `MapName` and `MapOutputName` attributes can only be added once at each level. You can add as many `MapInputName` attributes as you like!

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

Then specify it in the Mapping attribute:

```php{5,6}
use \App\Values\Mappers\Kebab;
use Bag\Bag;
use Bag\Mappers\Stringable;

#[MapInputName(Kebab::class)]
#[MapOutputName(Kebab::class)]
class MyValue extends Bag {
    public function __construct(
        public string $myValue,
        public string $myOtherValue
    ) {}
}
```
