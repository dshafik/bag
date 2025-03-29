# Wrapping Outputs

Bag supports wrapping both Bag values and Collections when transforming to an array or JSON.

## Wrapping Bags

To wrap a Bag, add the `Bag\Attributes\Wrap` or `Bag\Attributes\WrapJson` attribute to the class:

```php{4}
use Bag\Attributes\Wrap;
use Bag\Bag;

#[Wrap('data')]
class MyValue extends Bag {
    public function __construct(
        public string $name,
        public int $age,
    ) {}
}
```

Now whenever you call `->toArray()` or serialize to JSON, the output will be wrapped in a key of `data`:

```php
$myValue = MyValue::from(['name' => 'Davey Shafik', 'age' => 40]);
$myValue->toArray(); // ['data' => ['name' => 'Davey Shafik', 'age' => 40]]
$myValue->toJson(); // {"data":{"name":"Davey Shafik","age":40}}
```

If you only want to wrap when serializing to JSON, you can use the `WrapJson` attribute instead:

```php{4}
use Bag\Attributes\WrapJson;
use Bag\Bag;

#[WrapJson('data')]
class MyValue extends Bag {
    public function __construct(
        public string $name,
        public int $age,
    ) {}
}
```

Now when you serialize to JSON, the values will be wrapped, but when calling `->toArray()` the output will not be wrapped

```php
$myValue = MyValue::from(['name' => 'Davey Shafik', 'age' => 40]);
$myValue->toArray(); // ['name' => 'Davey Shafik', 'age' => 40]
$myValue->toJson(); // {"data":{"name":"Davey Shafik","age":40}}
```

> [!TIP]
> You can add both `Wrap` and `WrapJson` attributes to the same class to apply different wrapping to `->toArray()` and JSON serialization respectively.

## Wrapping Collections

Wrapping Collections works exactly the same way as with Bags: add the `Bag\Attributes\Wrap` or `Bag\Attributes\WrapJson` attribute to the Collection class:

```php{4}
use Bag\Attributes\Wrap;
use Bag\Collection;

#[Wrap('data')]
class MyCollection extends Collection {
}
```

Now when you call `->toArray()` or serialize to JSON, the output will be wrapped in a key of `data`:

```php
$collection = MyValue::factory()->count(2)->make();

$collection->toArray(); // ['data' => [["name" => "Davey Shafik", "age" => 40], ...]]
$collection->toJson(); // {"data":[{"name":"Davey Shafik","age":40}, ...]}
```
