<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use Bag\BagServiceProvider;
use Bag\Console\Commands\MakeBagCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tests\TestCase;

#[CoversClass(MakeBagCommand::class)]
#[RunTestsInSeparateProcesses]
class MakeBagCommandTest extends TestCase
{
    public function testItCreatesBag()
    {
        $this->artisan('make:bag')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php
            
            declare(strict_types=1);
            
            namespace App\Values;
            
            use Bag\Bag;
            
            readonly class MyBag extends Bag
            {
                public function __construct()
                {
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Values/MyBag.php'))
        );
    }

    public function testItCreatesBagWithFactory()
    {
        $this->artisan('make:bag --factory')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsQuestion('Factory class name', 'MyBagFactory')
            ->expectsOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBagFactory.php] created successfully.')
            ->expectsOutputToContain('Call this command again with --update to update the factory after defining the Bag constructor.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values;
            
            use App\Values\Factories\MyBagFactory;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Factory(MyBagFactory::class)]
            readonly class MyBag extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Values/MyBag.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values\Factories;
            
            use Bag\Factory;
            
            class MyBagFactory extends Factory
            {
                public function definition(): array
                {
                    return [
                    ];
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Values/Factories/MyBagFactory.php'))
        );
    }

    public function testItCreatesBagWithCollection()
    {
        $this->artisan('make:bag --collection')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsQuestion('Collection class name', 'MyBagCollection')
            ->expectsOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->expectsOutputToContain('Bag Collection [app/Values/Collections/MyBagCollection.php] created successfully.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values;
            
            use App\Values\Collections\MyBagCollection;
            use Bag\Attributes\Collection;
            use Bag\Bag;
            
            #[Collection(MyBagCollection::class)]
            readonly class MyBag extends Bag
            {
                public function __construct()
                {
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Values/MyBag.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php
            
            declare(strict_types=1);
            
            namespace App\Values\Collections;
            
            use Bag\Collection;
            
            class MyBagCollection extends Collection
            {
            }
            
            EOF,
            \file_get_contents(\app_path('Values/Collections/MyBagCollection.php'))
        );
    }

    public function testItCreatesBagWithFactoryAndCollection()
    {
        $this->artisan('make:bag --factory --collection')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsQuestion('Factory class name', 'MyBagFactory')
            ->expectsQuestion('Collection class name', 'MyBagCollection')
            ->expectsOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBagFactory.php] created successfully.')
            ->expectsOutputToContain('Bag Collection [app/Values/Collections/MyBagCollection.php] created successfully.')
            ->expectsOutputToContain('Call this command again with --update to update the factory after defining the Bag constructor.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values;
            
            use App\Values\Collections\MyBagCollection;
            use App\Values\Factories\MyBagFactory;
            use Bag\Attributes\Collection;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Collection(MyBagCollection::class)]
            #[Factory(MyBagFactory::class)]
            readonly class MyBag extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Values/MyBag.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values\Factories;
            
            use Bag\Factory;
            
            class MyBagFactory extends Factory
            {
                public function definition(): array
                {
                    return [
                    ];
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Values/Factories/MyBagFactory.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php
            
            declare(strict_types=1);
            
            namespace App\Values\Collections;
            
            use Bag\Collection;
            
            class MyBagCollection extends Collection
            {
            }
            
            EOF,
            \file_get_contents(\app_path('Values/Collections/MyBagCollection.php'))
        );
    }

    public function testItPretendsToCreateBag()
    {
        $this->artisan('make:bag --pretend')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->doesntExpectOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->expectsOutputToContain(
                <<<'EOF'
                <?php
                
                declare(strict_types=1);
                
                namespace App\Values;
                
                use Bag\Bag;
                
                readonly class MyBag extends Bag
                {
                    public function __construct()
                    {
                    }
                }
                
                EOF
            )
            ->assertExitCode(0);

        $this->assertFalse(file_exists(\app_path('Values/MyBag.php')));
        $this->assertFalse(file_exists(\app_path('Values/Factories/MyBagFactory.php')));
        $this->assertFalse(file_exists(\app_path('Values/Collections/MyBagCollection.php')));
    }

    public function testItPretendsToCreateBagWithFactory()
    {
        $this->artisan('make:bag --factory --pretend')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsQuestion('Factory class name', 'MyBagFactory')
            ->doesntExpectOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->expectsOutputToContain(
                <<<'EOF'
                <?php

                declare(strict_types=1);
                
                namespace App\Values;
                
                use App\Values\Factories\MyBagFactory;
                use Bag\Attributes\Factory;
                use Bag\Bag;
                use Bag\Traits\HasFactory;
                
                #[Factory(MyBagFactory::class)]
                readonly class MyBag extends Bag
                {
                    use HasFactory;
                
                    public function __construct()
                    {
                    }
                }
                
                EOF
            )
            ->expectsOutputToContain(
                <<<'EOF'
                <?php
    
                declare(strict_types=1);
                
                namespace App\Values\Factories;
                
                use Bag\Factory;
                
                class MyBagFactory extends Factory
                {
                    public function definition(): array
                    {
                        return [
                        ];
                    }
                }
                
                EOF,
            )
            ->assertExitCode(0);

        $this->assertFalse(file_exists(\app_path('Values/MyBag.php')));
        $this->assertFalse(file_exists(\app_path('Values/Factories/MyBagFactory.php')));
        $this->assertFalse(file_exists(\app_path('Values/Collections/MyBagCollection.php')));
    }

    public function testItPretendsToCreateBagWithCollection()
    {
        $this->artisan('make:bag --collection --pretend')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsQuestion('Collection class name', 'MyBagCollection')
            ->doesntExpectOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->doesntExpectOutputToContain('Bag Collection [app/Values/Collections/MyBagCollection.php] created successfully.')
            ->expectsOutputToContain(
                <<<'EOF'
                <?php
    
                declare(strict_types=1);
                
                namespace App\Values;
                
                use App\Values\Collections\MyBagCollection;
                use Bag\Attributes\Collection;
                use Bag\Bag;
                
                #[Collection(MyBagCollection::class)]
                readonly class MyBag extends Bag
                {
                    public function __construct()
                    {
                    }
                }
                
                EOF
            )
            ->expectsOutputToContain(
                <<<'EOF'
                <?php
                
                declare(strict_types=1);
                
                namespace App\Values\Collections;
                
                use Bag\Collection;
                
                class MyBagCollection extends Collection
                {
                }
                
                EOF,
            )
            ->assertExitCode(0);
    }

    public function testItPretendsToCreateBagWithFactoryAndCollection()
    {
        $this->artisan('make:bag --factory --collection --pretend')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsQuestion('Factory class name', 'MyBagFactory')
            ->expectsQuestion('Collection class name', 'MyBagCollection')
            ->doesntExpectOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
            ->doesntExpectOutputToContain('Bag Factory [app/Values/Factories/MyBagFactory.php] created successfully.')
            ->doesntExpectOutputToContain('Bag Collection [app/Values/Collections/MyBagCollection.php] created successfully.')
            ->doesntExpectOutputToContain('Call this command again with --update to update the factory after defining the Bag constructor.')
            ->expectsOutputToContain(
                <<<'EOF'
                <?php
    
                declare(strict_types=1);
                
                namespace App\Values;
                
                use App\Values\Collections\MyBagCollection;
                use App\Values\Factories\MyBagFactory;
                use Bag\Attributes\Collection;
                use Bag\Attributes\Factory;
                use Bag\Bag;
                use Bag\Traits\HasFactory;
                
                #[Collection(MyBagCollection::class)]
                #[Factory(MyBagFactory::class)]
                readonly class MyBag extends Bag
                {
                    use HasFactory;
                
                    public function __construct()
                    {
                    }
                }
                
                EOF
            )
            ->expectsOutputToContain(
                <<<'EOF'
                <?php
    
                declare(strict_types=1);
                
                namespace App\Values\Factories;
                
                use Bag\Factory;
                
                class MyBagFactory extends Factory
                {
                    public function definition(): array
                    {
                        return [
                        ];
                    }
                }
                
                EOF
            )
            ->expectsOutputToContain(
                <<<'EOF'
                <?php
                
                declare(strict_types=1);
                
                namespace App\Values\Collections;
                
                use Bag\Collection;
                
                class MyBagCollection extends Collection
                {
                }
                
                EOF
            )
            ->assertExitCode(0);
    }

    public function testItErrorsOnBagFileExists()
    {
        mkdir(\app_path('Values'), recursive: true);
        touch(\app_path('Values/MyBag.php'));

        $this->artisan('make:bag')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Values')
            ->expectsOutputToContain('The Bag file already exists. Use --force to overwrite, or --update to add factory/collection.')
            ->assertExitCode(1);
    }

    public function testItAcceptsNamespaceArg()
    {
        $this->artisan('make:bag --factory --collection --namespace=App\\\\Test')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Factory class name', 'MyBagFactory')
            ->expectsQuestion('Collection class name', 'MyBagCollection')
            ->expectsOutputToContain('Bag [app/Test/MyBag.php] created successfully.')
            ->expectsOutputToContain('Bag Factory [app/Test/Factories/MyBagFactory.php] created successfully.')
            ->expectsOutputToContain('Bag Collection [app/Test/Collections/MyBagCollection.php] created successfully.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test;
            
            use App\Test\Collections\MyBagCollection;
            use App\Test\Factories\MyBagFactory;
            use Bag\Attributes\Collection;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Collection(MyBagCollection::class)]
            #[Factory(MyBagFactory::class)]
            readonly class MyBag extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Test/MyBag.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test\Factories;
            
            use Bag\Factory;
            
            class MyBagFactory extends Factory
            {
                public function definition(): array
                {
                    return [
                    ];
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Test/Factories/MyBagFactory.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test\Collections;
            
            use Bag\Collection;
            
            class MyBagCollection extends Collection
            {
            }
            
            EOF,
            \file_get_contents(\app_path('Test/Collections/MyBagCollection.php'))
        );
    }

    public function testItAcceptsFactoryArgValue()
    {
        $this->artisan('make:bag --factory=TestFactory')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Test')
            ->expectsOutputToContain('Bag [app/Test/MyBag.php] created successfully.')
            ->expectsOutputToContain('Bag Factory [app/Test/Factories/TestFactory.php] created successfully.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test;
            
            use App\Test\Factories\TestFactory;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Factory(TestFactory::class)]
            readonly class MyBag extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Test/MyBag.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test\Factories;
            
            use Bag\Factory;
            
            class TestFactory extends Factory
            {
                public function definition(): array
                {
                    return [
                    ];
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Test/Factories/TestFactory.php'))
        );
    }

    public function testItAcceptsCollectionArgValue()
    {
        $this->artisan('make:bag --collection=TestCollection')
            ->expectsQuestion('Bag class name', 'MyBag')
            ->expectsQuestion('Namespace', 'App\Test')
            ->expectsOutputToContain('Bag [app/Test/MyBag.php] created successfully.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test;
            
            use App\Test\Collections\TestCollection;
            use Bag\Attributes\Collection;
            use Bag\Bag;
            
            #[Collection(TestCollection::class)]
            readonly class MyBag extends Bag
            {
                public function __construct()
                {
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Test/MyBag.php'))
        );

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test\Collections;
            
            use Bag\Collection;
            
            class TestCollection extends Collection
            {
            }
            
            EOF,
            \file_get_contents(\app_path('Test/Collections/TestCollection.php'))
        );
    }

    public function testItUpdates()
    {
        $this->artisan('make:bag MyBag --namespace=App\\\\Values')
            ->assertExitCode(0);

        $this->artisan('make:bag MyBag --namespace=App\\\\Values --update --factory --collection')
            ->expectsQuestion('Factory class name', 'MyBagFactory')
            ->expectsQuestion('Collection class name', 'MyBagCollection')
            ->expectsOutputToContain('Bag [app/Values/MyBag.php] updated successfully.')
            ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBagFactory.php] created successfully.')
            ->expectsOutputToContain('Bag Collection [app/Values/Collections/MyBagCollection.php] created successfully.')
            ->assertExitCode(0);
    }

    public function testItGeneratesFactory()
    {
        if (!\file_exists(\app_path('Values'))) {
            mkdir(\app_path('Values'), recursive: true);
        }

        file_put_contents(
            \app_path('Values/MyBag.php'),
            <<<'EOF'
            <?php
            
            declare(strict_types=1);
            
            namespace App\Values;
            
            use Bag\Attributes\Cast;
            use Bag\Bag;
            use Bag\Casts\CollectionOf;
            use Bag\Casts\DateTime;
            use Bag\Collection;
            use Brick\Money\Money;
            use Carbon\CarbonImmutable;
            
            readonly class MyBag extends Bag
            {
                public function __construct(
                    public string $name,
                    public int $age,
                    #[Cast(DateTime::class, 'y-m-d')]
                    public CarbonImmutable $birthday,
                    public Money $money,
                    public MyBag $test,
                    #[Cast(CollectionOf::class, MyBag::class)]
                    public Collection $collection,
                ) {
                }
            }
            
            EOF
        );

        $this->artisan('make:bag MyBag --namespace=App\\\\Values --update --factory=MyBagFactory')
            ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBagFactory.php] created successfully.')
            ->assertExitCode(0);

        $this->assertSame(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values\Factories;
            
            use App\Values\MyBag;
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
                        'test' => MyBag::empty(),
                        'collection' => MyBag::collect([MyBag::empty()]),
                    ];
                }
            }
            
            EOF,
            \file_get_contents(\app_path('Values/Factories/MyBagFactory.php'))
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            BagServiceProvider::class,
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $files = [
            \app_path('Values/MyBag.php'),
            \app_path('Values/Factories/MyBagFactory.php'),
            \app_path('Values/Collections/MyBagCollection.php'),
            \app_path('Values/Factories'),
            \app_path('Values/Collections'),
            \app_path('Values'),
            \app_path('Test/MyBag.php'),
            \app_path('Test/Factories/MyBagFactory.php'),
            \app_path('Test/Factories/TestFactory.php'),
            \app_path('Test/Collections/MyBagCollection.php'),
            \app_path('Test/Collections/TestCollection.php'),
            \app_path('Test/Factories'),
            \app_path('Test/Collections'),
            \app_path('Test'),
        ];

        foreach ($files as $file) {
            if (is_dir($file)) {
                rmdir($file);
            } elseif (file_exists($file)) {
                unlink($file);
            }
        }
    }
}
