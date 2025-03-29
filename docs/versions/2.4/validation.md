# Validation

Bag uses Laravel's validation system to validate input data. You can define validation rules using annotations, and/or using the `rules()` method.

`Bag` will automatically validate input data when creating a new instance using the `Bag::from()` method.

```php
use Bag\Attributes\Validation\Required;
use Bag\Bag;

readonly class MyValue extends Bag
{
    public function __construct(
        #[Required]
        public string $name,
        public int $age,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['string'],
            'age' => ['integer'],
        ];
    }
}
```

In this example we added a `#[Required]` attribute to the `name` property, and defined validation rules in the `rules()` method.

You can validate a Bag object before creating it using the `Bag::validate()` method:

```php
$value = MyValue::validate([
    'name' => 'Davey Shafik',
    'age' => 40,
]);
```

## Creating a Bag Without Validation

If you want to create a Bag without automatically validating it, you can use the `Bag::withoutValidation()` method:

```php
$value = MyValue::withoutValidation([
    'name' => 'Davey Shafik',
    'age' => 40,
]);
```

To futher append properties to a Bag without validation, you can use the `Bag::append()` method:

```php
$value = MyValue::withoutValidation([
    'name' => 'Davey Shafik',
])->append([
    'age' => 40,
]);
```

## Built-in Validation Attributes

Bag provides a number of built-in validation attributes, based on various Laravel validation rules:

| Rule                                                                          |                                                               | Usage                                      |
|-------------------------------------------------------------------------------|---------------------------------------------------------------|--------------------------------------------|
| [Between](https://laravel.com/docs/validation#rule-between)                   | The value should be between two values (inclusive)            | `#[Between(1, 10)]`                        |
| [Boolean](https://laravel.com/docs/validation#rule-boolean)                   | The value should be a boolean                                 | `#[Boolean]`                               |
| [Decimal](https://laravel.com/docs/validation#rule-decimal)                   | The value should be a decimal number                          | `#[Decimal]`                               |
| [Email](https://laravel.com/docs/validation#rule-email)                       | The value should be an email address                          | `#[Email]`                                 |
| [Enum](https://laravel.com/docs/validation#rule-enum)                         | The value should be an enum case                              | `#[Enum(MyEnum::class)]`                   |
| [Exists](https://laravel.com/docs/validation#rule-exists)                     | The value must exist in a field in a table                    | `#[Exists('table', 'optionalColumn')]`     |
| [In](https://laravel.com/docs/validation#rule-in)                             | The value should be in the given list                         | `#[In('foo', 'bar')]`                      |
| [Integer](https://laravel.com/docs/validation#rule-integer)                   | The value should be an integer                                | `#[Integer]`                               |
| [Max](https://laravel.com/docs/validation#rule-max)                           | The value should be at most a given size                      | `#[Max(100)]`                              |
| [Min](https://laravel.com/docs/validation#rule-min)                           | The value should be at minimum a given size                   | `#[Min(1)]`                                |
| [NotRegex](https://laravel.com/docs/validation#rule-not-regex)                | The value should not match a given regex                      | `#[NotRegex('/regex/')]`                   |
| [Numeric](https://laravel.com/docs/validation#rule-numeric)                   | The value should be numeric                                   | `#[Numeric]`                               |
| [Regex](https://laravel.com/docs/validation#rule-regex)                       | The value should match a given regex                          | `#[Regex('/regex/')]`                      |
| [Required](https://laravel.com/docs/validation#rule-required)                 | The value is required                                         | `#[Required]`                              |
| [RequiredIf](https://laravel.com/docs/validation#rule-required-if)            | The value is required if another field matches a value        | `#[RequiredIf('otherField', 'value')]`     |
| [RequiredUnless](https://laravel.com/docs/validation#rule-required-unless)    | The value is required unless another field matches a value    | `#[RequiredUnless('otherField', 'value')]` |
| [RequiredWith](https://laravel.com/docs/validation#rule-required-with)        | The value is required if another field is present             | `#[RequiredWith('otherField')]`            |
| [RequiredWithAll](https://laravel.com/docs/validation#rule-required-with-all) | The value is required if more than one other field is present | `#[RequiredWithAll('field1', 'field2')]`   |
| [Size](https://laravel.com/docs/validation#rule-size)                         | The value should have a specific size                         | `#[Size(10)]`                              |
| [Str](https://laravel.com/docs/validation#rule-string)                        | The value should be a string                                  | `#[Str]`                                   |
| [Unique](https://laravel.com/docs/validation#rule-unique)                     | The value must be unique for a field in a table               | `#[Unique('table', 'optionalColumn')]`     |

In addition, a generic `\Bag\Attributes\Validation\Rule` attribute is available to apply any Laravel validation rule:

```php
use Bag\Attributes\Validation\Rule;

readonly class MyValue extends Bag
{
    public function __construct(
        #[Required]
        public string $username,
        public string $password,
        #[Rule('same:password')]
        public string $passwordConfirmation,
    ) {
    }
}
```
