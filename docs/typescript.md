# TypeScript

This package can convert Bag Value Objects to TypeScript types using the 
[spatie/typescript-transformer](https://spatie.be/docs/typescript-transformer) package.

Bag will automatically convert `Optional` properties to TypeScript `property?: otherTypes` properties, and will
apply output name mapping to the property names to match JSON output.

## Configuration

To configure the `spatie/typescript-transformer` package to transform Bags correctly.

### Laravel

If you are using Bag with Laravel and the `spatie/laravel-typescript-transformer` package, you must replace the `Spatie\TypeScriptTransformer\Transformers\DtoTransformer` with
the `\Bag\TypeScript\BagTransformer` in the `transformers` array of the `config/typescript-transformer.php` configuration file:

```diff{4-5}
'transformers' => [
    Spatie\LaravelTypeScriptTransformer\Transformers\SpatieStateTransformer::class,
    Spatie\TypeScriptTransformer\Transformers\SpatieEnumTransformer::class,
--  Spatie\TypeScriptTransformer\Transformers\DtoTransformer::class,
++  Bag\TypeScript\BagTransformer::class,
],
```

### Framework Agnostic

If you are not using Laravel, you can follow the instruction [here](https://spatie.be/docs/typescript-transformer/v2/usage/getting-started),
and just be sure to add the `\Bag\TypeScript\BagTransformer` to the `transformers()` method call. 

## Usage

Once the package has been configured, you can use the `spatie/typescript-transformer` package as normal, and Bags will be transformed
as expected.

For example, running the transformer with the following Bag:

```php{9-10,15-16}
namespace App\Values;

use Bag\Attributes\MapOutputName;
use Bag\Bag;
use Bag\Mappers\SnakeCase;
use Bag\Values\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapOutputName(SnakeCase::class)]
class MyValue extends Bag
{
    public function __construct(
        public string $name,
        public Optional|int $age,
        public Optional|string|null $emailAddress = null,
    ) {}
}
```

will result in output something like:

```typescript{4-5}
declare namespace App.Values {
    export type MyValue = {
        name: string;
        age?: number;
        email_address?: string | null;
    };
}
```

> [!WARNING]
> If you do not make the necessary configuration changes, Bags will not be transformed correctly, resulting instead in
> `property: any | otherTypes`.

### Collections

If you want to output Bag Collections as types, in addition to the `#[TypeScript]` annotation, you can
also add a `#[LiteralTypeScriptType]` annotation to map it to a typed array of the Value object type:

```php{8}
namespace App\Values\Collections;

use Bag\Collection;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript];
#[LiteralTypeScriptType('App.Values.MyValue[]')]
class MyValueCollection extends Collection
{
}
```

Which will result in TypeScript output similar to:

```typescript{2}
declare namespace App.Values.Collections {
    export type MyValueCollection = App.Values.MyValue[];
}
```

