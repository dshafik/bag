# Creating Bags From Objects

Bag provides an easy way to create Bags from objects with the `Bag\Traits\HasBag` trait. 

This trait provides a `->toBag()` method that converts the object into a Bag object using the Bag class
defined in the `Bag\Attributes\Bag` class attribute.

## Adding `HasBag` To Your Class

```php{5,7}
use App\Values\MyValue;
use Bag\Attributes\Bag;
use Bag\Traits\HasBag;

#[Bag(MyValue::class)]
class MyClass {
    use HasBag;
    
    // ... 
} 
```

Once you have made these changes to your class, you can easily create a Bag object from an instance of your class by calling the `->toBag()` method:

```php
$myClass = new MyClass();
$bag = $myClass->toBag();
```

## Property Visibility

By default, the `Bag\Traits\HasBag` trait will only include public properties in the Bag object. If you would like to include protected and private properties, you can pass the `visibility` argument to the `Bag\Attributes\Bag` attribute.

The visibility property is a bitmask of the following values:

- `Bag\Attributes\Bag::PUBLIC` - Include public properties
- `Bag\Attributes\Bag::PROTECTED` - Include protected properties
- `Bag\Attributes\Bag::PRIVATE` - Include private properties
- `Bag\Attributes\Bag::ALL` - Include all properties

```php{5}
use App\Values\MyValue;
use Bag\Attributes\Bag;
use Bag\Traits\HasBag;

#[Bag(MyValue::class, Bag::PUBLIC | Bag::PROTECTED)]
class MyClass {
    use HasBag;
    
    // ... 
} 
```

> [!TIP]
> You can also specify the visibility when calling the `->toBag()` method e.g. `$myClass->toBag(Bag::ALL)`
