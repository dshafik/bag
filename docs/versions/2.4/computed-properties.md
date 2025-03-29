# Computed Properties

Bag supports computed properties, these are properties that are derived at creation time rather than passed in.

Failing to set a computed property will result in a `Bag\Exceptions\ComputedPropertyMissing` exception.

## Using Computed Properties

To use computed properties, define the property in your class, and add the `Bag\Attributes\Computed` attributed:

```php{7,11}
use Bag\Bag;
use Bag\Attributes\Computed;
use Carbon\CarbonImmutable;

class MyValue extends Bag
{
    #[Computed]
    public string $computedProperty;
    
    public function __construct() {
        $this->computedProperty = CarbonImmutable::now();
    }
}
```

> [!WARNING]
> You **must** set the property within the constructor, otherwise an exception will be thrown.
