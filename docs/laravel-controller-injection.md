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

## Avoiding Extra Parameters

By default, Bag will throw a `\Bag\Exceptions\AdditionalPropertiesException` exception if you try to instantiate a non-variadic Bag with extra parameters. You can disable this behavior by adding the `StripExtraParameters` attribute to the controller parameter:

```php
use App\Values\MyValue;
use Bag\Attributes\StripExtraParameters;

class MyController extends Controller {
    public function store(
        #[StripExtraParameters] MyValue $value
    ) {
        // After stripping extra parameters, $value is a validated MyValue object
    }
}
```

> [!TIP]
> You can also add the `StripExtraParameters` attribute to [the Bag class itself](./basic-usage.md#stripping-extra-parameters). 

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

> [!NOTE]
> This will also strip additional parameters from the request data that are not part of the `Bag` object.

> [!TIP]
> The `Bag->valid()` method will throw a `ValidationException` if the Bag object is not valid by default, you can pass in `false` to return null instead.

> [!CAUTION]
> The input values must still fulfill the requirements of the Bag constructor, all required properties must be present otherwise an exception will be thrown.
