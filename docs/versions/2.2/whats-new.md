# What's New in Bag 2.2

## `var_export()` support

Bag 2.2 adds support for `var_export()` to enable exporting and importing Bag objects and Collections correctly. 

```php
use Bag\Bag;

$bag = MyBag::from(['foo' => 'bar']);

var_export($bag, true); 
// \Tests\Fixtures\Values\MyBag::__set_state(array(
//  'foo' => 'bar'
// ))
```

This feature is useful for debugging and testing purposes.

## `AdditionalPropertiesException` message improvements

The `AdditionalPropertiesException` message has been improved to include the class name to make it easier to debug.

```php
Additional properties found for bag (\App\Values\MyBag): extra, foo
```

