# Output

Once you have created a Bag value object, you can access the properties using object notation:

```php
use App\Values\MyValue;

$value = new MyValue(name: 'Davey Shafik', age: 40);
$value->name; // 'Davey Shafik'
```

## Casting to Other Types

Bag supports casting to arrays and [`\Bag\Collections`](./collections.md##extending-laravel-collections) using the
`Bag->toArray()` and `Bag->toCollection()` methods.

Both methods will apply casting and mapping, as well as respect [hidden properties](./hidden).

## JSON Serialization

In addition, you can serialize a Bag object to JSON using `json_encode()` or `Bag->toJson():

```php
$value = MyValue::from(name: 'Davey Shafik', age: 40);

$value->toJson(); // {"name": "Davey Shafik", "age": 40}
json_encode($value, JSON_THROW_ON_ERROR); // {"name": "Davey Shafik", "age": 40}
```

Both `Bag->toJson()` and `json_encode()` will respect [hidden properties](./hidden).
