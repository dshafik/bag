# Laravel Controller Injection

Bag can automatically inject Bag objects into your controllers using Laravel's automatic dependency injection. This can take
the place of using Laravel's form request validation _and_ accessing the input data.

```php
use App\Values\MyValue;

class MyController extends Controller {
    public function store(MyValue $value) {
        // $value is a validated MyValue object
    }
}
```

## Automatic Validation 

When you type hint a `Bag` object in your controller method, Bag will automatically validate the request data and inject the `Bag` object into your controller method.

## Manual Validation

If you want to inject the `Bag` object without validation, you can add the `WithoutValidation` attribute to the property:

```php
use App\Values\MyValue;
use Bag\Attributes\WithoutValidation;

class MyController extends Controller {
    public function store(
        #[WithoutValidation] MyValue $value
    ) {
        $value = $value->append(extra: 'data')->valid();
        // $value is now a validated MyValue object
    }
}
```

> [!TIP]
> The `Bag->valid()` method will throw a `ValidationException` if the Bag object is not valid by default, you can pass in `false` to return null instead.

> [!CAUTION]
> The input values must still fulfill the requirements of the Bag constructor, all required properties must be present otherwise an exception will be thrown.
