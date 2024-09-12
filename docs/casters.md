# Built-in Casters

The following built-in explicit casters are available:

## Bag Collections

`\Bag\Casts\CollectionOf` casts an array to a [Collection](/collections) of Bag objects.

```php
use Bag\Bag;
use Bag\Attributes\Cast;
use Bag\Casts\CollectionOf;
use Bag\Collection;

class MyValue extends Bag {
    public function __construct(
        #[Cast(CollectionOf::class, MyOtherValue::class)]    
        public Collection $values,
    ) {
    }
}

$value = MyValue::from([
    'values' => [
        ['name' => 'Davey Shafik', 'age' => 40],
        …
    ],
]);

dump($value->values); // Collection<MyOtherValue>
```

> [!TIP]
> The `CollectionOf` caster will use the appropriate Collection class based on the Bag class provided.

## Dates & Time

`\Bag\Casts\DateTime` casts a date/time string to an object that implements the `\DateTimeInterface` interface.

```php
use Bag\Bag;
use Bag\Attributes\Cast;
use Bag\Casts\DateTime;
use Carbon\CarbonImmutable;

class MyValue extends Bag {
    public function __construct(
        #[Cast(
            DateTime::class, 
            format: 'u', 
            outputFormat: 
            'Y-m-d', strictMode: true
        )]
        public CarbonImmutable $dateOfBirth,
    ) {
    }
}

$value = MyValue::from([
    // Requires a UNIX timestamp input due to strictMode: true
    'dateOfBirth' => '454809600', 
]);

$value->dateOfBirth; // CarbonImmutable{date: 1984-05-31 00:00:00 UTC}
$value->toArray(); // ['dateOfBirth' => '1984-05-31'] due to outputFormat
```

The `DateTime` cast accepts the following parameters:

- `format` - The format of the input and output date/time string (see [PHP date/time format](https://www.php.net/manual/en/datetimeimmutable.createfromformat.php#datetimeimmutable.createfromformat.parameters))
- `outputFormat` - The format of the output date/time string, this will override the `format` parameter for output only
- `strictMode` - If `true`, the input date/time string must match the `format` parameter exactly, otherwise it is parsed as best as possible
- `dateTimeClass` - The class to use for the date/time object, must implement `\DateTimeInterface`, this must be compatible with the property type hint, and defaults to the type hint value

> [!TIP]
> We recommend using `CarbonImmutable` as type for all date/time casting.

## Money

`\Bag\Casts\MoneyFromMinor` Casts a number to a `\Brick\Money\Money` object assuming minor units (e.g. Cents)

```php
use Bag\Bag;
use Bag\Attributes\Cast;
use Bag\Casts\MoneyFromMinor;
use Brick\Money\Money;

class MyValue extends Bag {
    public function __construct(
        #[Cast(MoneyFromMinor::class, currency: 'USD')]
        public Money $amount,
    ) {
    }
}

$value = MyValue::from([
    'amount' => 1000,
]);

dump($value->amount); // Money object with a value of 10.00 USD
```

`\Bag\Casts\MoneyFromMajor` Casts a number to a `\Brick\Money\Money` object assuming major units (e.g. Dollars)

```php
use Bag\Bag;
use Bag\Attributes\Cast;
use Bag\Casts\MoneyFromMajor;
use Brick\Money\Money;

class MyValue extends Bag {
    public function __construct(
        #[Cast(MoneyFromMajor::class, currency: 'USD')]
        public Money $amount,
    ) {
    }
}

$value = MyValue::from([
    'amount' => 1000,
]);

dump($value->amount); // Money object with a value of 1,000.00 USD
```

Both `MoneyFromMajor` and `MoneyFromMinor` accept the following parameters:

- `currency` - The currency code to use for the `\Brick\Money\Money` object, either a 3-letter ISO 4217 code or a [`\PrinsFrank\Standards\Currency\CurrencyAlpha3`](https://github.com/PrinsFrank/standards/blob/main/src/Currency/CurrencyAlpha3.php#L22) enum case.
- `currencyProperty` - The input parameter to use for the currency code
- `locale` - The locale to use for formatting the money object, defaults to `en_US`

> [!TIP]
> Best practices recommend that you always handle money as strings of minor units, _however_ if you are working with user input it's most likely in major units.
> We recommend using `MoneyFromMajor` as the input caster and `MoneyFromMinor` as the output caster for handling user input.
