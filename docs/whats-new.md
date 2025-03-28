# What's New in Bag 2.5

## Optional Properties

Bag 2.5 adds support for optional properties using the `Optional` class. This allows you to define properties that can be omitted when creating a Bag object.

Optional properties will _also_ be omitted when outputting the Bag object as an array or JSON.

```php
use Bag\Bag;

class MyValue extends Bag
{
    public function __construct(
        public string $name,
        public Optional|int $age,
        public Optional|string|null $email = null,
    ) {}
}

$value = new MyValue(name: 'Davey Shafik', email: null);
$value->toArray(); // ['name' => 'Davey Shafik', 'email' => null]
$value->toJson(); // {"name": "Davey Shafik", "email": null}
```

Read more in the [documentation](./optionals).

## Nullable `DateTimeInterface` properties

Prior to Bag 2.5, if you had a nullable `DateTimeInterface` property (e.g. `?\Carbon\CarbonImmutable`) it would attempt to create the
`DateTimeInterface` object with `null` and fail with a `TypeError`. Bag 2.5 will instead assign the value to `null`.

```php
use Bag\Bag;

class MyValue extends Bag
{
    public function __construct(
        public ?DateTimeInterface $date = null,
    ) {}
}

$value = new MyValue(date: null); // Bag < 2.5 TypeError, 2.5+ sets null
```
