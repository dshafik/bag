# Generate Bag Value Objects, Collections, and Factories

Bag includes the `make:bag` artisan command to make it easy to generate new Bag classes, Factories, and Collections.

> [!NOTE] Namespaces
> The `make:bag` command will use the provided namespace to determine the location of the generated classes.
> You can set the namespace using the `--namespace` option or you will be prompted for it. The default namespace is `\App\Values`.

## Generating Bags

To create a new Bag class, use the `make:bag` command:

```bash
php artisan make:bag MyBag
```

This will create a new `MyBag` class in `app/Values/MyBag.php`:

```php
<?php

declare(strict_types=1);

namespace App\Values;

use Bag\Bag;

readonly class MyBag extends Bag
{
    public function __construct() {
    }
}
```

## Generating Bag Collections

To create a new Bag Collection class, use the `make:bag` command with the `--collection` option:

```bash
php artisan make:bag MyBag --collection
```

You can optionally specify the name of the collection class:

```bash
php artisan make:bag MyBag --collection=MyBagCollection
```

If none is specified, it will use prompt you for the collection name, and defaults to the Bag class name with `Collection` appended to it.

This will create a new `MyBagCollection` class in `app/Values/Collections/MyBagCollection.php`.

```php
<?php

declare(strict_types=1);

namespace App\Values\Collections;

use Bag\Collection;

class MyBagCollection extends Collection
{
}
```

> [!TIP]
> When creating a new Bag _or_ when specifying the `--update` flag, it will automatically add the `Collection` attribute to the Bag class.

## Generating Bag Factories

The `make:bag` command can also generate Bag Factory classes, **and** will _automatically_ generate the `definition()` function based on the Bag class properties.

To create a new Bag Factory class, use the `make:bag` command with the `--factory` option:

```bash
php artisan make:bag MyBag --factory
```

This will create a new `MyBagFactory` class in `app/Values/Factories/MyBagFactory.php`:

```php
<?php

declare(strict_types=1);

namespace App\Values\Factories;

use Bag\Factory;

class TestFactory extends Factory
{
    public function definition(): array
    {
        return [
        ];
    }
}
```

> [!TIP]
> When creating a new Bag _or_ when specifying the `--update` flag, it will automatically add the `Factory` attribute and `HasFactory` trait to the Bag class.

### Updating Factories

Once you had added properties to your Bag class, you can update the Factory class using the `--update` option:

```bash
php artisan make:bag MyBag --factory --update
```

> [!TIP]
> If you update the Bag class properties, you can re-run the `make:bag` command with the `--update` and `--force-excluding-bag` options to update the Factory class.

For example, if we update our `MyBag` constructor to look like the following:

```php
public function __construct(
    public string $name,
    public int $age,
    #[Cast(DateTime::class, 'y-m-d')]
    public CarbonImmutable $birthday,
    public Money $money,
    public AnotherBag $test,
    #[Cast(CollectionOf::class, AnotherBag::class)]
    public Collection $collection,
) {
}
```

The generated factory will look like this:

```php
<?php

declare(strict_types=1);

namespace App\Values\Factories;

use App\Values\AnotherBag;
use Bag\Factory;
use Brick\Money\Money;
use Carbon\CarbonImmutable;

class MyBagFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'age' => $this->faker->randomNumber(),
            'birthday' => new CarbonImmutable(),
            'money' => Money::ofMinor($this->faker->numberBetween(100, 10000), 'USD'),
            'test' => AnotherBag::factory()->make(),
            'collection' => AnotherBag::collect([AnotherBag::factory()->make()]),
        ];
    }
}
```

## Expected Usage

Because the factory is based on the Bag class properties, you will typically create and customize the Bag value object, and **then** create
the factory. To do this, you would follow these steps:

1. Create the Bag class (optionally, with a collection):

```bash
php artisan make:bag MyBag --collection
```

2. Customize the Bag class

3. Create the Bag Factory:

```bash
php artisan make:bag MyBag --factory --update
```

If you have already created the factory, you must add the `--force-except-bag` option to overwrite it:

```bash
php artisan make:bag MyBag --factory --update --force-except-bag
```

> [!WARNING]
> If you use the `--force` option instead of `--force-except-bag` it will overwrite your customized Bag Value class, losing any customizations.

## The `make:bag` Command

The `make:bag` command has the following options:

```
Description:
  Create a new Bag value class, with optional factory and collection.

Usage:
  make:bag [options] [--] <name>

Arguments:
  name                           

Options:
  -F, --force                    Force overwriting all files
  -E, --force-except-bag         Force overwriting Factory/Collection files
  -u, --update                   Update Bag class to add factory/collection
  -f, --factory[=FACTORY]        Create a Factory for the Bag [default: "interactive"]
  -c, --collection[=COLLECTION]  Create a Collection for the Bag [default: "interactive"]
  -N, --namespace[=NAMESPACE]    Specify the namespace for the Bag
      --pretend                  Dump the file contents instead of writing to disk
  -h, --help                     Display help for the given command. When no command is given display help for the list command
  -q, --quiet                    Do not output any message
  -V, --version                  Display this application version
      --ansi|--no-ansi           Force (or disable --no-ansi) ANSI output
  -n, --no-interaction           Do not ask any interactive question
      --env[=ENV]                The environment the command should run under
  -v|vv|vvv, --verbose           Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
