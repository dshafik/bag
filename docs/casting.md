# Casting Values

Casting allows you to format input and output values appropriately. 

## Explicit Casting

You can explicitly cast input and/or output values to a specific type using annotations. This allows you to cast simple values to complex values,
or simply transform it in some way. For example, you can cast a number to a `\Brick\Money\Money` object or a timestamp to a `\Carbon\CarbonImmutable` object.  

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

### Available Casts

Bag supports several [built-in casters](/casters), and you can create your own by implementing `CastsPropertySet` and/or `CastsPropertyGet`.


## Automatic Casting

If no cast attribute is provided, input values are _automatically_ cast to the correct type based on the type hint of the property when possible.

The following types are automatically cast:

- Scalar types (i.e. `int`, `float`, `bool`, `string`)
- `\CarbonImmutable`, `\Carbon`, `\DateTimeImmutable`, and `\DateTime` objects (parsing the value using [PHP date/time formats](https://www.php.net/manual/en/datetime.formats.php))
- `Bag` objects from arrays
- Laravel Collections (i.e. `collect($value)`)
- BackedEnums from value (i.e. `public FooEnum $foo` will cast the value `BAR` using `FooEnum::from('BAR')`)
- UnitEnums by name (i.e. `public FooEnum $foo` will cast the value `BAR` to `FooEnum::BAR`)
- Laravel Models from IDs (using `Model::findOrFail($value)`)

All other types will be set to the input value (which may be invalid). Input objects that match the type hint are not re-cast.

For example, given the following Bag class:

```php
use Bag\Bag;
use Carbon\CarbonImmutable;

class MyValue extends Bag {
    public function __construct(
        public CarbonImmutable $dateOfBirth,
    ) {
    }
}
```

When you create a new instance of `MyValue`, the `dateOfBirth` property will be a `\Carbon\CarbonImmutable` object:

```php
MyValue::from([
    'dateOfBirth' => '1984-05-31',
]);
```

However, if you pass in a value like `12:34:56`, it will result in a `\Carbon\CarbonImmutable` object with the current date and the time set to `12:34:56`.

> [!WARNING]
> To avoid this issue, we recommend always using a `DateTime` cast as shown above, setting the `strictMode` parameter to `true`. This allows you to specify a required input format.

When you access the `dateOfBirth` property directly, it will be the `\Carbon\CarbonImmutable` object.
