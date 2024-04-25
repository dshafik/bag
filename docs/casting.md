# Casting Values

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

