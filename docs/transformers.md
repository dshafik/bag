# Transformers

Transformers are helpers that transform Bag input data from custom types, e.g. Models or JSON strings.

## Using Transformers

Transformers are methods defined on your Bag class that transform input data into the correct type. Transformers are
identified by adding the `Transforms` attribute to the method, passing the type you want to transform from.

For example, to transform from a JSON string:

```php
use Bag\Bag;

class MyValue extends Bag
{
    #[Transforms('string')]
    protected static function fromJsonString(string $json): array
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
```

or to transform from a specific class:

```php
use App\Models\User;
use Bag\Bag;

class MyValue extends Bag
{
    #[Transforms(User::class)]
    protected static function fromUser(User $user): array
    {
        return $user->toArray();
    }
}
```

You can pass multiple types to the `Transforms` attribute, or use multiple `Transforms` attributes to handle multiple types.

The following two examples are functionally the same:

```php
use App\Models\Book;
use App\Models\Magazine;
use Bag\Bag;

class MyValue extends Bag
{
    #[Transforms(Book::class)]
    #[Transforms(Magazine::class)]
    protected static function fromMedia(Book|Magazine $media): array
    {
        return $media->toArray();
    }
}
```

and:

```php
use App\Models\Book;
use App\Models\Magazine;
use Bag\Bag;

class MyValue extends Bag
{
    #[Transforms(Book::class, Magazine::class)]
    protected static function fromMedia(Book|Magazine $media): array
    {
        return $media->toArray();
    }
}
```

Bag will match child classes to their parents, so `Transforms(Model::class)` will match any child `Model` objects, however,
if there is a more specific transformer available, Bag will use that instead.

> [!TIP]
> Bag will use the most specific transformer available, if two or more transformers are equally specific then Bag will use the first one it finds.

### Handling JSON

By default, Bag will transform JSON strings, but you can override this behavior by defining a overriding the `fromJsonString` method:

```php
use Bag\Bag;

class MyValue extends Bag
{
    #[Transforms(Bag::FROM_JSON)]
    protected static function fromJsonString(string $json): array
    {
        return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
    }
}
```

To differentiate between other strings and JSON, you should use the special type `Bag::FROM_JSON` as the `Transformer` type.

