# Variadic Properties

Bag supports the use of [Variadic](https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list) arguments.

Variadic arguments **must** be manually assigned to a property:

```php
use Bag\Bag;

class MyValue {
    public $values;

    public function __construct(mixed ...$values) {
        $this->values = $values;
    }
}
```

## Casting

Bag will automatically cast variadic values to their defined typed:

```php
use Bag\Bag;

class MyValue {
    public $values;

    public function __construct(bool ...$values) {
        $this->values = $values;
    }
}
```

The above example will cast all values to `bool`:

```php
$bag = new MyValue(true, false, 0, 1);

// $bag->values = [true, false, false, true]
```

