# Laravel Route Parameter Binding

Bag can automatically populate properties from Laravel Route Parameters. This can be useful when you have both a 
Bag representing your request body, and a route parameter that you want to associate with the Bag.

## Binding Route Parameters

To bind a route parameter, use the `Bag\Attributes\Laravel\FromRouteParameter` attribute on the property you want to bind.

For example given the following controller:

```php
use App\Values\MyValue;

class MyController {
    public function show(MyValue $bag, string $id) {
        // $bag is automatically injected and validated
        // $id is a route parameter
    }
}
```

You can create a Bag like this that will automatically populate the `id` property based on the route parameter as injected above:

```php{6}
use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Bag;

class MyValue extends Bag
{
    #[FromRouteParameter()]
    public string $id;
}
```

This will automatically populate the `id` property from the route parameter with the same name as the property.

You can also use a different property name just by passing it to the attribute:

```php{6}
use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Bag;

class MyValue extends Bag
{
    #[FromRouteParameter('id')]
    public string $valueId;
}
```

This will automatically popualte the `valueId` property from the route parameter with the name `id`.

## Binding Route Parameter Properties

In addition to binding the entire route parameter value to a single property, you can also bind a property or array key
from that value to your Bag using the `Bag\Attributes\Laravel\FromRouteParameterProperty` attribute.

For example, given the following controller:

```php
use App\Models\User;
use App\Values\MyValue;

class MyController {
    public function show(MyValue $bag, User $user) {
        // $bag is automatically injected and validated
        // $user is a route parameter
    }
}
```

We can map the `id` property from the `User` model to a property on the Bag like this:

```php{6}
use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Bag;

class MyValue extends Bag
{
    #[FromRouteParameterProperty('user')]
    public string $id;
}
```

This will automatically populate the `id` property with the `id` property from the `user` route parameter. 
Alternatively, you can specify a source property name:

```php{6}
use Bag\Attributes\Laravel\FromRouteParameter;
use Bag\Bag;

class MyValue extends Bag
{
    #[FromRouteParameterProperty('user', 'id')]
    public string $userId;
}
```

This will automatically populate the property `userId` with the `id` property from the `user` route parameter.


## Types & Casting

The value from the route parameter will be cast by Laravel to the type specified in the controller method,
and will then be cast to the type of the property in the Bag if necessary.

For example, if the route parameter method argument is an integer, and the property is a string, the integer will be cast to a string.
However, if the route parameter is an eloquent model and the property is also the same eloquent model, the value will not be cast.

> [!TIP]
> This works great for route model binding!
