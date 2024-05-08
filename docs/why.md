# Why Choose Bag?

Bag is a simple, lightweight, modern, and flexible library for working with value objects in PHP. 

It is designed to be easy to use, with a minimal API that is easy to understand and use.

Bag focuses on immutability and type safety.

## Compared to spatie/laravel-data

Bag was heavily inspired by the excellent [spatie/laravel-data](https://spatie.be/docs/laravel-data/) package, and as such
should feel very familiar to anyone who has used it — _however_, it has several key differences.

## Common Features

- [Value Casting](casting) (both in and out, although spatie/laravel-data calls outbound casting Transforming)
  - Including nested Bag objects
- [Name Mapping](mapping) (both in and out) at the class and property level
- [Validation](validation) (although spatie/laravel-data does not support all Laravel validation options easily)
- [Collections](collections) of Value Objects[*](#collections)
- [Object to Bag](object-to-bag) conversion
- [Wrapping](wrapping) of output arrays/JSON
- [Eloquent Casting](laravel-eloquent-casting)
- [Laravel Controller Injection](laravel-controller-injection)

## Immutability

Bag is immutable by default. 

spatie/laravel-data does not support immutable value objects, and as of PHP 8.3, there is no reasonable way to make them immutable.

## Factory Support

Bag [factories](testing) support many of the rich features and simple UX as Laravel Model Factories with the exception of not having a `create()` method (as value objects do not feature persistence). 
This includes support for [factory states](https://laravel.com/docs/11.x/eloquent-factories#factory-states) and [sequences](https://laravel.com/docs/11.x/eloquent-factories#sequences).

spatie/laravel-data v3 does not support factories, while v4 has [rudimentary support](https://spatie.be/docs/laravel-data/v4/as-a-data-transfer-object/factories).

## Variadic Support

Bag supports the use of [Variadic](variadics) during value object creation. 

## Collections

Bag uses Laravel Collections as the basis for its [Collection](collections) classes, and supports them wherever Collections are used, however `Bag\Collection` is an immutable-safe variant that we recommend
using whenever possible. 

spatie/laravel-data v3 uses a custom `DataCollection` class that is not based on Laravel collections and lacks many Collection features. v4 uses Laravel Collections, although it still 
has [custom collection classes](https://spatie.be/docs/laravel-data/v4/as-a-data-transfer-object/collections) with varying levels of compatibility with Laravel Collections.

## Hidden Properties

Bag supports [hiding properties](hidden) when transforming to an array or JSON.

## Other Differences

In addition to the above, Bag has a few other minor differences:

- [Casters](casting) apply to both incoming values and outgoing values, while spatie/laravel-data splits these into two difference concepts.
- Input from complex values uses [Transformers](transformers), which are more explicit in Bag, while spatie/laravel-data uses a more implicit approach with magic methods e.g. `::fromModel()`
- Simpler attribute names: `Cast` vs `WithCast` and `WithTransformer`
