<?php

declare(strict_types=1);

namespace Bag\Console\Commands;

use Bag\Attributes\Cast;
use Bag\Attributes\Collection as CollectionAttribute;
use Bag\Attributes\Factory as FactoryAttribute;
use Bag\Bag;
use Bag\Casts\CollectionOf;
use Bag\Collection;
use Bag\Factory;
use Bag\Internal\Reflection;
use Bag\Traits\HasFactory;
use Brick\Money\Money;
use DateTimeInterface;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\text;
use Nette\PhpGenerator\Literal;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Nette\PhpGenerator\TraitType;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use ReflectionClass;
use ReflectionNamedType;

class MakeBagCommand extends Command implements PromptsForMissingInput
{
    protected $signature = 'make:bag {name} 
        {--F|force : Force overwriting all files} 
        {--E|force-except-bag : Force overwriting Factory/Collection files} 
        {--u|update : Update Bag class to add factory/collection/docs}
        {--f|factory=interactive : Create a Factory for the Bag}
        {--c|collection=interactive : Create a Collection for the Bag}
        {--d|docs : Add Bag::from() docs to the Bag for IDE auto-complete}
        {--N|namespace= : Specify the namespace for the Bag} 
        {--pretend : Dump the file contents instead of writing to disk}';

    protected $description = 'Create a new Bag value class, with optional factory and collection.';

    public function handle(): int
    {
        /** @var string $name */
        $name = $this->argument('name');

        /** @var string $namespace */
        $namespace = match ($this->option('namespace')) {
            null => text('Namespace', default: 'App\\Values'),
            default => $this->option('namespace')
        };

        /** @var string $factory */
        $factory = match ($this->option('factory')) {
            'interactive' => null,
            null => text('Factory class name', default: $name . 'Factory'),
            default => $this->option('factory')
        };

        /** @var string $collection */
        $collection = match ($this->option('collection')) {
            'interactive' => null,
            null => text('Collection class name', default: $name . 'Collection'),
            default => $this->option('collection')
        };

        $directory = app_path(Str::of($namespace)->after('App\\')->replace('\\', '/')->toString());

        $this->ensureDirectory($directory);
        if (!$this->createBag($namespace, $name, $directory, $factory, $collection, (bool) $this->option('docs'))) {
            return 1;
        }

        if ($factory !== null && !$this->createFactory($namespace, $factory, $name, $directory)) {
            return 1;
        }

        if ($collection !== null && !$this->createCollection($namespace, $collection, $directory)) {
            return 1;
        }

        if ($factory !== null && !$this->option('update') && !$this->option('pretend')) {
            outro('Call this command again with --update to update the factory after defining the Bag constructor.');
        }

        return 0;
    }

    /**
     * @return array<string, callable>
     */
    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => fn () => text('Bag class name', required: true),
        ];
    }

    protected function createBag(string $namespace, string $name, string $directory, ?string $factory, ?string $collection, bool $docs): bool
    {
        $classFile = Str::of($directory)->finish('/')->append($name)->append('.php')->toString();

        if (file_exists($classFile) && !$this->option('force') && !$this->option('update') && !$this->option('pretend')) {
            $this->components->error('The Bag file already exists. Use --force to overwrite, or --update to add factory/collection/docs.');

            return false;
        }

        if (file_exists($classFile) && !$this->option('force') && $this->option('update')) {
            if ($factory !== null || $collection !== null || $docs !== false) {
                $this->updateBag($namespace, $name, $classFile, $factory, $collection);

                return true;
            }

            return false;
        }

        $file = new PhpFile();
        $file->setStrictTypes();
        $classNamespace = $file->addNamespace($namespace);

        $classNamespace->addUse(Bag::class);

        $class = $classNamespace->addClass($name);
        if ($this->option('docs')) {
            $class->addComment('@method static static from(array $values)');
        }
        $class->setReadOnly();
        $class->setExtends(Bag::class);
        $class->addMethod('__construct')
            ->setPublic();

        if ($collection !== null) {
            $alias = $this->getCollectionAlias($classNamespace);
            $classNamespace->addUse(CollectionAttribute::class, $alias);
            $classNamespace->addUse($namespace . '\\Collections\\' . $collection);
            $class->addAttribute(CollectionAttribute::class, [new Literal($collection . '::class')]);
        }

        if ($factory !== null) {
            $classNamespace->addUse(FactoryAttribute::class);
            $classNamespace->addUse(HasFactory::class);
            $classNamespace->addUse($namespace . '\\Factories\\' . $factory);
            $class->addAttribute(FactoryAttribute::class, [new Literal($factory . '::class')]);
            $class->addTrait(HasFactory::class);
        }

        // In order for us to use reflection on the class, we need to write it to a temporary file for require_once
        if ($this->option('pretend')) {
            $classFile = \tempnam(\sys_get_temp_dir(), 'bag');
        }

        file_put_contents((string) $classFile, (new PsrPrinter())->printFile($file));

        require_once $classFile;

        if ($this->option('pretend')) {
            $this->pretend($name, (new PsrPrinter())->printFile($file));

            \unlink((string) $classFile);

            return true;
        }

        $this->components->info('Bag [' . $classFile . '] created successfully.');

        return true;
    }

    protected function updateBag(string $namespace, string $name, string $classFile, ?string $factory, ?string $collection): void
    {
        require_once $classFile;

        $file = PhpFile::fromCode((string) file_get_contents($classFile));
        $classNamespace = $file->getNamespaces()[$namespace];
        /** @var TraitType $class */
        $class = $classNamespace->getClasses()[$name];

        /** @var class-string $fullyQualifiedName */
        $fullyQualifiedName = $classNamespace->getName() . '\\' . $name;
        if ($factory !== null) {
            $classNamespace->addUse(FactoryAttribute::class);
            $classNamespace->addUse(HasFactory::class);
            $classNamespace->addUse($namespace . '\\Factories\\' . $factory);

            if (count((new ReflectionClass($fullyQualifiedName))->getAttributes(FactoryAttribute::class)) > 0) {
                $attributes = $class->getAttributes();
                foreach ($attributes as $key => $attribute) {
                    if ($attribute->getName() === FactoryAttribute::class) {
                        unset($attributes[$key]);
                    }
                }
                $class->setAttributes($attributes);
            }

            $class->addAttribute(FactoryAttribute::class, [new Literal($factory . '::class')]);

            $class->removeTrait(HasFactory::class);
            $class->addTrait(HasFactory::class);
        }

        if ($collection !== null) {
            if (count((new ReflectionClass($fullyQualifiedName))->getAttributes(CollectionAttribute::class)) > 0) {
                $attributes = $class->getAttributes();
                foreach ($attributes as $key => $attribute) {
                    if ($attribute->getName() === CollectionAttribute::class) {
                        unset($attributes[$key]);
                    }
                }
                $class->setAttributes($attributes);
            }

            $alias = $this->getCollectionAlias($classNamespace);
            $classNamespace->addUse(CollectionAttribute::class, $alias);
            $classNamespace->addUse($namespace . '\\Collections\\' . $collection);

            $class = $classNamespace->getClasses()[$name];

            $class->addAttribute(CollectionAttribute::class, [new Literal($collection . '::class')]);
        }

        if ($this->option('docs')) {
            $args = [];

            /** @var class-string $fullyQualifiedName */
            $fullyQualifiedName = $namespace . '\\' . $name;
            $parameters = (new ReflectionClass($fullyQualifiedName))->getMethod('__construct')->getParameters();
            foreach ($parameters as $parameter) {
                /** @var ReflectionNamedType $parameterType */
                $parameterType = $parameter->getType();
                $parameterTypeName = $parameterType->getName();

                if (!$parameterType->isBuiltin()) {
                    $classNamespace->addUse($parameterTypeName);

                    $collectionOf = '';
                    if ($parameterTypeName === Collection::class || \is_subclass_of($parameterTypeName, Collection::class, true)) {
                        $castAttribute = Reflection::getAttribute($parameter, Cast::class);

                        if ($castAttribute !== null && $castAttribute->getArguments()[0] === CollectionOf::class && $castAttribute->getArguments()[1] !== null) {
                            $classNamespace->addUse($castAttribute->getArguments()[1]);

                            $collectionOf = '<' . \class_basename($castAttribute->getArguments()[1]) . '>';
                        }
                    }

                    $parameterTypeName = class_basename($parameterTypeName) . $collectionOf;
                }

                if ($parameterType->allowsNull() && $parameterType->getName() !== 'mixed') {
                    $parameterTypeName = '?' . $parameterTypeName;
                }

                $args[] = $parameterTypeName . ' $' . $parameter->getName();
                if ($parameter->isDefaultValueAvailable()) {
                    if ($parameter->isDefaultValueConstant()) {
                        $args[count($args) - 1] .= ' = ' . $parameter->getDefaultValueConstantName();
                    } else {
                        $args[count($args) - 1] .= ' = ' . Str::of(var_export($parameter->getDefaultValue(), true))
                                ->replace(['NULL', 'FALSE', 'TRUE'], ['null', 'false', 'true']);
                    }
                }
            }

            $comment = Str::of($class->getComment() ?? '')
                ->replaceMatches('/^@method static static from\(.*$/', '')
                ->append("\n@method static static from(" . implode(', ', $args) . ')')
                ->ltrim("\n");

            $class->setComment($comment->toString());
        }

        if ($this->option('pretend')) {
            $classFile = \tempnam(\sys_get_temp_dir(), 'bag');
        }

        file_put_contents((string) $classFile, (new PsrPrinter())->printFile($file));

        if ($this->option('pretend')) {
            $this->pretend($name, (new PsrPrinter())->printFile($file));

            \unlink((string) $classFile);

            return;
        }

        $this->components->info('Bag [' . $classFile . '] updated successfully.');
    }

    protected function createFactory(string $namespace, string $name, string $valueName, string $directory): bool
    {
        $directory = $directory . '/Factories';
        $classFile = $directory . '/' . $name . '.php';

        if (\file_exists($classFile) && !$this->option('force') && !$this->option('force-except-bag') && !$this->option('pretend')) {
            $this->components->error('The Bag Factory file already exists. Use --force-except-bag to overwrite.');

            return false;
        }

        $this->ensureDirectory($directory);

        $file = new PhpFile();
        $file->setStrictTypes();
        $factoryNamespace = $file->addNamespace($namespace . '\\Factories');
        $factoryNamespace->addUse(Factory::class);

        $class = $factoryNamespace->addClass($name);
        $class->setExtends(Factory::class);
        $definition = $class->addMethod('definition')
            ->setPublic()
            ->setReturnType('array');

        $body = 'return [';

        /** @var class-string $fullyQualifiedName */
        $fullyQualifiedName = $namespace . '\\' . $valueName;
        $parameters = (new ReflectionClass($fullyQualifiedName))->getMethod('__construct')->getParameters();
        foreach ($parameters as $parameter) {
            $faker = 'null';

            // @phpstan-ignore method.notFound
            $parameterName = $parameter->getType()?->getName();

            // @phpstan-ignore method.notFound
            if (!$parameter->getType()?->isBuiltin()) {
                $type = new ReflectionClass($parameterName);

                if ($parameterName !== Collection::class) {
                    $factoryNamespace->addUse($parameterName);
                }

                if ($type->isSubclassOf(Bag::class)) {
                    $faker = class_basename($parameterName) . '::empty()';
                    if (is_callable([$parameterName, 'factory'])) {
                        $faker = class_basename($parameterName) . '::factory()->make()';
                    }
                }

                if ($parameterName === Money::class) {
                    $faker = 'Money::ofMinor($this->faker->numberBetween(100, 10000), \'USD\')';
                }

                if ($type->isSubclassOf(DateTimeInterface::class)) {
                    $faker = 'new ' . \class_basename($parameterName) . '()';
                }

                if ($parameterName === Collection::class || $type->isSubclassOf(Collection::class)) {
                    $collectionOf = $parameter->getAttributes(Cast::class)[0]->getArguments()[1];
                    $factoryNamespace->addUse($collectionOf);

                    $values = class_basename($collectionOf) . '::empty()';
                    if (\is_callable([$collectionOf, 'factory'])) {
                        $values = class_basename($collectionOf) . '::factory()->make()';
                    }
                    $faker = class_basename($collectionOf) . '::collect([' . $values . '])';
                }

                if ($type->isEnum()) {
                    $faker = 'array_rand(' . class_basename($parameterName) . '::cases())';
                }

                if ($parameterName === CurrencyAlpha3::class) {
                    $faker = 'CurrencyAlpha3::US_Dollar';
                }
            } else {
                $type = false;
                // @phpstan-ignore method.notFound
                if (!$parameter->getType()->isBuiltin()) {
                    $type = new ReflectionClass($parameterName);
                }

                // @phpstan-ignore method.notFound
                $parameterName = $parameter->getType()->getName();
                $faker = match (true) {
                    $parameterName === 'string' && ($parameter->name === 'id' || Str::endsWith($parameter->name, 'Id')) => '$this->faker->uuid()',
                    $parameterName === 'string' => '$this->faker->word()',
                    $parameterName === 'int' => '$this->faker->randomNumber()',
                    $parameterName === 'float' => '$this->faker->randomFloat()',
                    $parameterName === 'bool' => '$this->faker->boolean()',
                    $parameterName === 'array' => '[]',
                    $type !== false && $type->implementsInterface(DateTimeInterface::class) => 'new ' . $parameterName . '()',
                    default => 'null',
                };

                if ($type !== false && $type->implementsInterface(DateTimeInterface::class)) {
                    $factoryNamespace->addUse($parameterName);
                }
            }

            $body .= "\n\t'" . $parameter->getName() . '\' => ' . $faker . ',';
        }

        $body .= "\n];";

        $definition->setBody($body);

        if ($this->option('pretend')) {
            $this->pretend($namespace . '\\Factories\\' . $name, (new PsrPrinter())->printFile($file));

            return true;
        }

        file_put_contents($classFile, (new PsrPrinter())->printFile($file));

        $this->components->info('Bag Factory [' . $classFile . '] created successfully.');

        return true;
    }

    protected function createCollection(string $namespace, string $name, string $directory): bool
    {
        $directory = $directory . '/Collections';
        $classFile = $directory . '/' . $name . '.php';

        if (\file_exists($classFile) && !$this->option('force') && !$this->option('force-except-bag') && !$this->option('pretend')) {
            $this->components->error('The Bag Collection file already exists. Use --force-except-bag to overwrite.');

            return false;
        }

        $this->ensureDirectory($directory);

        $file = new PhpFile();
        $file->setStrictTypes();
        $namespace = $file->addNamespace($namespace . '\\Collections');
        $namespace->addUse(Collection::class);

        $factory = $namespace->addClass($name);
        $factory->setExtends(Collection::class);

        if ($this->option('pretend')) {
            $this->pretend($namespace->getName() . $name, (new PsrPrinter())->printFile($file));

            return true;
        }

        file_put_contents($classFile, (new PsrPrinter())->printFile($file));

        $this->components->info('Bag Collection [' . $classFile . '] created successfully.');

        return true;
    }

    protected function ensureDirectory(string $directory): void
    {
        if (!\file_exists($directory)) {
            mkdir($directory, recursive: true);
        }
    }

    protected function pretend(string $name, string $content): void
    {
        $this->components->twoColumnDetail($name);
        $this->comment($content);
    }

    protected function getCollectionAlias(PhpNamespace $classNamespace): ?string
    {
        $uses = $classNamespace->getUses();
        $alias = null;
        foreach ($uses as $use) {
            if (\str_ends_with($use, '\\Collection')) {
                $alias = 'CollectUsing';

                break;
            }
        }

        return $alias;
    }
}
