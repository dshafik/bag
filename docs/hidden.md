# Hidden Properties

Bag supports hiding properties when transforming to an array and/or JSON. 
This is useful when you want to keep certain properties private, or when you want to hide sensitive information.

Hidden properties are still accessible as object properties (e.g. `$bag->hiddenProperty`), but they will not be included
in the output when transforming to an array and/or JSON.

## Hiding Properties

Properties can be hidden by applying the `Hidden` attribute to the property:

```php
use Bag\Attributes\Hidden;
use Bag\Bag;

class MyValue extends Bag {
    public function __construct(
        #[Hidden]
        public string $hiddenProperty
        public string $visibleProperty
    ) {}
}
```

In the above example, the `hiddenProperty` will not be included when calling `toArray()` or `json_encode()`:

```php
$value = MyValue::from([
    'hiddenProperty' => 'hidden',
    'visibleProperty' => 'visible'
]);

$value->toArray(); // ['visibleProperty' => 'visible']
json_encode($value, JSON_THROW_ON_ERROR); // {"visibleProperty": "visible"}
```

Additionally, you can use PHP's built-in `SensitiveParameter` attribute to hide sensitive information:

```php
use Bag\Bag;
use SensitiveParameter;

class MyValue extends Bag {
    public function __construct(
        #[SensitiveParameter]
        public string $password
    ) {}
}
```

## Hiding Properties from JSON

In addition to always hiding properties, you can choose to _only_ hide them when serializing to JSON by using the `HiddenFromJson` attribute:

```php
use Bag\Attributes\HiddenFromJson;
use Bag\Bag;

class MyValue extends Bag {
    public function __construct(
        #[HiddenFromJson]
        public string $hiddenProperty
        public string $visibleProperty
    ) {}
}
```

In the above example, the `hiddenProperty` will be included when calling `json_encode()`, but not when calling `toArray()`:

```php
$value = MyValue::from([
    'hiddenProperty' => 'hidden',
    'visibleProperty' => 'visible'
]);

$value->toArray(); // ['hiddenProperty' => 'hidden', visibleProperty' => 'visible']
json_encode($value, JSON_THROW_ON_ERROR); // {"visibleProperty": "visible"}
```
