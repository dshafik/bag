# Collections

## Using Collections

You can create a collection of Value objects using the `Bag::collect()` method:

```php
$values = MyValue::collect([
    [
        'name' => 'Davey Shafik',
        'age' => 40,
    ],
    [
        'name' => 'Taylor Otwell',
        'age' => 40,
    ],
]);
```

This will create a `\Bag\Collection` of `MyValue` objects.

### Extending Laravel Collections

`Bag\Collection` extends `\Illuminate\Support\Collection` and has [most of the same methods](https://laravel.com/docs/12.x/collections#available-methods).

The following methods will throw an exception if used:

- `->pull()`
- `->shift()`
- `->splice()`
- `->transform()`
- `->getOrPut()`
- `offsetSet()` (used with array access)
- `offsetUnset()` (used with array access)
- `__set()` (used when setting arbitrary properties)

In addition, the following will create a new `Bag\Collection` instance instead of modifying the original:

- `->forget()`
- `->pop()`
- `->prepend()`
- `->push()`
- `->put()`

## Custom Collections

If you want to use a custom collection class, you must first create a new class that extends `\Bag\Collection`:

```php
use Bag\Collection;

class MyValueCollection extends Collection {
}
```

Then you can use this collection class in your Value object by adding the `Collection` attribute to your Bag class:

```php
use App\Values\Collections\MyValueCollection;
use Bag\Bag;
use Bag\Attributes\Collection;

#[Collection(MyValueCollection::class)
readonly class MyValue extends Bag {
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }
}
```

When using `MyValue::collect()` a `MyValueCollection` object will be returned.
