# Optionals

Bag supports optional parameters using the `Optional` class. `Optional` parameters are parameters 
that can be omitted when creating a Bag object, and will automatically be excluded from array and JSON 
representations.

> [!NOTE]
> Optional parameters are _different_ from nullable parameters, nulls are still included in outputs (array or JSON), 
> and can be combined with `Optional` which will not be. Optional parameters **will not** be filled with nulls when omitted.
> Optionals will not be persisted to the database when using [Eloquent Casting](./laravel-eloquent-casting).

> [!WARNING]
> You _must_ specify at least one other type. Optionals cannot be combined with `mixed`.

To make a property optional, use a union type that includes `Optional`:

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
```

In the above example, the `age` property is optional, and can be omitted when creating a `MyValue` object:

```php
$value = new MyValue(name: 'Davey Shafik', email: null);

$value->toArray(); // ['name' => 'Davey Shafik', 'email' => null]
$value->toJson(); // {"name": "Davey Shafik", "email": null}
```

In the above example, the `age` property is omitted, and the `email` property is explicitly set to `null`. Omitting the `email` property would result in an `Optional` being used.

## Value Presence

Because optional properties are set to the `Optional` class, a simple `isset()` check will not work. Instead, you must use the `->has()`, `->hasAll()`, and `->hasAny()` methods instead:

```php
$value = new MyValue(name: 'Davey Shafik', email: null);

$value->has('name'); // true
$value->has('age'); // false
$value->has('email'); // true

$value->hasAny('age', 'email'); // true

$value->hasAll('age', 'email'); // false
```


## Validation

When validating optional properties, `Optional` values will _not be included_ in the validated values. If you want to allow `Optional` _or_ validate the value, you can use the `OptionalOr` rule.

> [!WARNING]
> Using `Optional` with validation rules that require a value (like `required`, `integer`, etc.) will result in validation errors without the use of `OptionalOr`.

The `OptionalOr` rule will pass validation if the property is set to `Optional`, but will run the validation rules on the value if it is not:

```php
use Bag\Bag;
use Bag\Validation\Rules\OptionalOr;

class MyValue extends Bag
{
    public function __construct(
        public string $name,
        public Optional|int $age,
        public Optional|string|null $email = null,
    ) {}

    public function rules(): array
    {
        return [
            'age' => ['required', 'integer'],
            'email' => [new OptionalOr(['nullable', 'email'])],
        ];
    }
}

// The following are valid:

$value = new MyValue(name: 'Davey Shafik', age: 40);

$value = new MyValue(name: 'Davey Shafik', age: 40, email: null);
 
$value = new MyValue(name: 'Davey Shafik', age: 40, email: 'davey@php.net');

// While these are invalid:

$value = new MyValue(name: 'Davey Shafik', email: null)

$value = new MyValue(name: 'Davey Shafik', age: '40'); 

$value = new MyValue(name: 'Davey Shafik', age: null); 

$value = new MyValue(name: 'Davey Shafik', email: 'foo'); 
```

The `OptionalOr` class accepts an array of rules, a single string rule, or a class name that the value should be an `instanceof`.

> [!TIP]
> You can use the `Bag::withoutValidation()` method or the `#[WithoutValidation]` attribute to skip validation when creating a Bag value object to skip validation.
> 
> The `Bag->valid()` method will allow you to check if the Bag is valid at any time. 
