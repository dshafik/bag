# Eloquent Casting

Bag supports Eloquent casting for both Bag objects and Collections.

## Casting Bag Objects

To cast a Bag object, use the Bag class name as the cast type in the `$casts` property of your Eloquent model:

```php{7}
use Illuminate\Database\Eloquent\Model;
use App\Values\MyValue;

class MyModel extends Model
{
    protected $casts = [
        'my_value' => MyValue::class,
    ];
}
```

This will cast the `MyValue` object to a JSON string when saving it to the database and will cast it back to a `MyValue` object when retrieving it from the database.

> [!WARNING]
> Bag will store _all_ properties, including hidden properties.

## Casting Collections

To cast a Collection you can use the `AsBagCollection` class as the cast type in the `$casts` property of your Eloquent model, passing in the Bag class name as the first argument:

```php{8}
use Illuminate\Database\Eloquent\Model;
use App\Values\MyValue;
use Bag\AsBagCollection;

class MyModel extends Model
{
    protected $casts = [
        'my_values' => AsBagCollection::class . ':' . MyValue::class,
    ];
}
```

Alternatively, you can use the `casts()` method along with the `::castAsCollection()` method on your Bag class:

```php{8}
use Illuminate\Database\Eloquent\Model;
use App\Values\MyValue;

class MyModel extends Model
{
    public function casts(): array {
        return [
            'my_values' => MyValue::castAsCollection(),
        ];
    }
}
```

or the `AsBagCollection::of()` method:

```php{8}
use Illuminate\Database\Eloquent\Model;
use App\Values\MyValue;
use Bag\AsBagCollection;

class MyModel extends Model
{
    protected $casts = [
        'my_values' => AsBagCollection::of(MyValue::class),
    ];
}
```

Bag will cast the Collection to a JSON string when saving to the database and will cast them back to a Collection of `MyValue` objects when retrieving them from the database.

Bag will automatically use the custom collection class if one is defined on the Bag class.
