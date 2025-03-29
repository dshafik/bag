# Optionals

Bag supports optional parameters using the `Optional` class. `Optional` parameters are parameters 
that can be omitted when creating a Bag object, and will automatically be excluded from array and JSON 
representations.

> [!NOTE]
> Optional parameters are _different_ from nullable parameters, nulls are still included in the output, 
> and can be combined with `Optional`. Optional parameters **will not** be filled with nulls when omitted. 

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

Because Optional properties have a value (an `Optional` object), validation may not work like expected. Bag provides
a custom wrapper rule `\Bag\Validation\Rules\Optional` that can be used to validate optional properties correctly.

The `Optional` rule will pass validation if the property is set to Optional, but will run the validation rules on the value if it is not:

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
            'age' => [new OptionalOr(['required', 'integer'])],
            'email' => [new OptionalOr(['nullable', 'email'])],
        ];
    }
}

// All of the following are valid

// both age and email are optional
$value = new MyValue(name: 'Davey Shafik'); 
// age is an int, email is optional
$value = new MyValue(name: 'Davey Shafik', age: 40); 
// age is optional, email is nullable
$value = new MyValue(name: 'Davey Shafik', email: null) 
// age is integer, email is an email
$value = new MyValue(name: 'Davey Shafik', age: 40, email: 'davey@php.net') 

// While these are invalid:

// age is not an int, email is optional
$value = new MyValue(name: 'Davey Shafik', age: '40'); 

// age is required, email is optional
$value = new MyValue(name: 'Davey Shafik', age: null); 

// email is not an email, age is optional
$value = new MyValue(name: 'Davey Shafik', email: 'foo'); 
```

The `OptionalOr` class accepts an array of rules, a single string rule, or a class name that the value should be an `instanceof`.
