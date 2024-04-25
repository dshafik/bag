# Laravel Injection

Bag can automatically inject _validated_ Bag objects into your controllers using Laravel's automatic dependency injection. This can take
the place of using Laravel's form request validation _and_ accessing the input data.

```php
use App\Values\MyValue;

class MyController extends Controller {
    public function store(MyValue $value) {
        // $value is a validated MyValue object
    }
}
```

When you type hint a `Bag` object in your controller method, Bag will automatically validate the request data and inject the `Bag` object into your controller method.

