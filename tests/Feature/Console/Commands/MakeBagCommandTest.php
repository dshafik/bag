<?php

declare(strict_types=1);

test('it creates bag', function () {
    $this->artisan('make:bag')
        ->expectsQuestion('Bag class name', 'MyBag')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsOutputToContain('Bag [app/Values/MyBag.php] created successfully.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Values/MyBag.php')))->toBe(<<<'EOF'
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
            
            EOF);
});

test('it creates bag with factory', function () {
    $this->artisan('make:bag --factory')
        ->expectsQuestion('Bag class name', 'MyBag2')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsQuestion('Factory class name', 'MyBag2Factory')
        ->expectsOutputToContain('Bag [app/Values/MyBag2.php] created successfully.')
        ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBag2Factory.php] created successfully.')
        ->expectsOutputToContain('Call this command again with --update to update the factory after defining the Bag constructor.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Values/MyBag2.php')))->toBe(
        <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values;
            
            use App\Values\Factories\MyBag2Factory;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Factory(MyBag2Factory::class)]
            readonly class MyBag2 extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF
    )
        ->and(file_get_contents(app_path('Values/Factories/MyBag2Factory.php')))->toBe(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values\Factories;
            
            use Bag\Factory;
            
            class MyBag2Factory extends Factory
            {
                public function definition(): array
                {
                    return [
                    ];
                }
            }
            
            EOF
        );

});

test('it creates bag with collection', function () {
    $this->artisan('make:bag --collection')
        ->expectsQuestion('Bag class name', 'MyBag3')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsQuestion('Collection class name', 'MyBag3Collection')
        ->expectsOutputToContain('Bag [app/Values/MyBag3.php] created successfully.')
        ->expectsOutputToContain('Bag Collection [app/Values/Collections/MyBag3Collection.php] created successfully.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Values/MyBag3.php')))->toBe(
        <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values;
            
            use App\Values\Collections\MyBag3Collection;
            use Bag\Attributes\Collection;
            use Bag\Bag;
            
            #[Collection(MyBag3Collection::class)]
            readonly class MyBag3 extends Bag
            {
                public function __construct()
                {
                }
            }
            
            EOF
    )
        ->and(file_get_contents(app_path('Values/Collections/MyBag3Collection.php')))->toBe(
            <<<'EOF'
            <?php
            
            declare(strict_types=1);
            
            namespace App\Values\Collections;
            
            use Bag\Collection;
            
            class MyBag3Collection extends Collection
            {
            }
            
            EOF
        );

});

test('it creates bag with factory and collection', function () {
    $this->artisan('make:bag --factory --collection')
        ->expectsQuestion('Bag class name', 'MyBag4')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsQuestion('Factory class name', 'MyBag4Factory')
        ->expectsQuestion('Collection class name', 'MyBag4Collection')
        ->expectsOutputToContain('Bag [app/Values/MyBag4.php] created successfully.')
        ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBag4Factory.php] created successfully.')
        ->expectsOutputToContain('Bag Collection [app/Values/Collections/MyBag4Collection.php] created successfully.')
        ->expectsOutputToContain('Call this command again with --update to update the factory after defining the Bag constructor.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Values/MyBag4.php')))->toBe(
        <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values;
            
            use App\Values\Collections\MyBag4Collection;
            use App\Values\Factories\MyBag4Factory;
            use Bag\Attributes\Collection;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Collection(MyBag4Collection::class)]
            #[Factory(MyBag4Factory::class)]
            readonly class MyBag4 extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF
    )
        ->and(file_get_contents(app_path('Values/Factories/MyBag4Factory.php')))->toBe(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values\Factories;
            
            use Bag\Factory;
            
            class MyBag4Factory extends Factory
            {
                public function definition(): array
                {
                    return [
                    ];
                }
            }
            
            EOF
        )
        ->and(file_get_contents(app_path('Values/Collections/MyBag4Collection.php')))->toBe(
            <<<'EOF'
            <?php
            
            declare(strict_types=1);
            
            namespace App\Values\Collections;
            
            use Bag\Collection;
            
            class MyBag4Collection extends Collection
            {
            }
            
            EOF
        );

});

test('it pretends to create bag', function () {
    $this->artisan('make:bag --pretend')
        ->expectsQuestion('Bag class name', 'MyBag5')
        ->expectsQuestion('Namespace', 'App\Values')
        ->doesntExpectOutputToContain('Bag [app/Values/MyBag5.php] created successfully.')
        ->expectsOutputToContain(
            <<<'EOF'
                <?php
                
                declare(strict_types=1);
                
                namespace App\Values;
                
                use Bag\Bag;
                
                readonly class MyBag5 extends Bag
                {
                    public function __construct()
                    {
                    }
                }
                
                EOF
        )
        ->assertExitCode(0);

    expect(file_exists(app_path('Values/MyBag5.php')))->toBeFalse()
        ->and(file_exists(app_path('Values/Factories/MyBag5Factory.php')))->toBeFalse()
        ->and(file_exists(app_path('Values/Collections/MyBag5Collection.php')))->toBeFalse();
});

test('it pretends to create bag with factory', function () {
    $this->artisan('make:bag --factory --pretend')
        ->expectsQuestion('Bag class name', 'MyBag7')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsQuestion('Factory class name', 'MyBag7Factory')
        ->doesntExpectOutputToContain('Bag [app/Values/MyBag7.php] created successfully.')
        ->expectsOutputToContain(
            <<<'EOF'
                <?php

                declare(strict_types=1);
                
                namespace App\Values;
                
                use App\Values\Factories\MyBag7Factory;
                use Bag\Attributes\Factory;
                use Bag\Bag;
                use Bag\Traits\HasFactory;
                
                #[Factory(MyBag7Factory::class)]
                readonly class MyBag7 extends Bag
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
                
                class MyBag7Factory extends Factory
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

    expect(file_exists(app_path('Values/MyBag7.php')))->toBeFalse()
        ->and(file_exists(app_path('Values/Factories/MyBag7Factory.php')))->toBeFalse()
        ->and(file_exists(app_path('Values/Collections/MyBag7Collection.php')))->toBeFalse();
});

test('it pretends to create bag with collection', function () {
    $this->artisan('make:bag --collection --pretend')
        ->expectsQuestion('Bag class name', 'MyBag8')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsQuestion('Collection class name', 'MyBag8Collection')
        ->doesntExpectOutputToContain('Bag [app/Values/MyBag8.php] created successfully.')
        ->doesntExpectOutputToContain('Bag Collection [app/Values/Collections/MyBag8Collection.php] created successfully.')
        ->expectsOutputToContain(
            <<<'EOF'
                <?php
    
                declare(strict_types=1);
                
                namespace App\Values;
                
                use App\Values\Collections\MyBag8Collection;
                use Bag\Attributes\Collection;
                use Bag\Bag;
                
                #[Collection(MyBag8Collection::class)]
                readonly class MyBag8 extends Bag
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
                
                class MyBag8Collection extends Collection
                {
                }
                
                EOF,
        )
        ->assertExitCode(0);
});

test('it pretends to create bag with factory and collection', function () {
    $this->artisan('make:bag --factory --collection --pretend')
        ->expectsQuestion('Bag class name', 'MyBag9')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsQuestion('Factory class name', 'MyBag9Factory')
        ->expectsQuestion('Collection class name', 'MyBag9Collection')
        ->doesntExpectOutputToContain('Bag [app/Values/MyBag9.php] created successfully.')
        ->doesntExpectOutputToContain('Bag Factory [app/Values/Factories/MyBag9Factory.php] created successfully.')
        ->doesntExpectOutputToContain('Bag Collection [app/Values/Collections/MyBag9Collection.php] created successfully.')
        ->doesntExpectOutputToContain('Call this command again with --update to update the factory after defining the Bag constructor.')
        ->expectsOutputToContain(
            <<<'EOF'
                <?php
    
                declare(strict_types=1);
                
                namespace App\Values;
                
                use App\Values\Collections\MyBag9Collection;
                use App\Values\Factories\MyBag9Factory;
                use Bag\Attributes\Collection;
                use Bag\Attributes\Factory;
                use Bag\Bag;
                use Bag\Traits\HasFactory;
                
                #[Collection(MyBag9Collection::class)]
                #[Factory(MyBag9Factory::class)]
                readonly class MyBag9 extends Bag
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
                
                class MyBag9Factory extends Factory
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
                
                class MyBag9Collection extends Collection
                {
                }
                
                EOF
        )
        ->assertExitCode(0);
});

test('it errors on bag file exists', function () {
    if (!file_exists(app_path('Values'))) {
        mkdir(app_path('Values'), recursive: true);
    }

    touch(app_path('Values/MyBag10.php'));

    $this->artisan('make:bag')
        ->expectsQuestion('Bag class name', 'MyBag10')
        ->expectsQuestion('Namespace', 'App\Values')
        ->expectsOutputToContain('The Bag file already exists. Use --force to overwrite, or --update to add factory/collection.')
        ->assertExitCode(1);
});

test('it accepts namespace arg', function () {
    $this->artisan('make:bag --factory --collection --namespace=App\\\\Test')
        ->expectsQuestion('Bag class name', 'MyBag11')
        ->expectsQuestion('Factory class name', 'MyBag11Factory')
        ->expectsQuestion('Collection class name', 'MyBag11Collection')
        ->expectsOutputToContain('Bag [app/Test/MyBag11.php] created successfully.')
        ->expectsOutputToContain('Bag Factory [app/Test/Factories/MyBag11Factory.php] created successfully.')
        ->expectsOutputToContain('Bag Collection [app/Test/Collections/MyBag11Collection.php] created successfully.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Test/MyBag11.php')))->toBe(
        <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test;
            
            use App\Test\Collections\MyBag11Collection;
            use App\Test\Factories\MyBag11Factory;
            use Bag\Attributes\Collection;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Collection(MyBag11Collection::class)]
            #[Factory(MyBag11Factory::class)]
            readonly class MyBag11 extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF
    )
        ->and(file_get_contents(app_path('Test/Factories/MyBag11Factory.php')))->toBe(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test\Factories;
            
            use Bag\Factory;
            
            class MyBag11Factory extends Factory
            {
                public function definition(): array
                {
                    return [
                    ];
                }
            }
            
            EOF
        )
        ->and(file_get_contents(app_path('Test/Collections/MyBag11Collection.php')))->toBe(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test\Collections;
            
            use Bag\Collection;
            
            class MyBag11Collection extends Collection
            {
            }
            
            EOF
        );

});

test('it accepts factory arg value', function () {
    $this->artisan('make:bag --factory=TestFactory')
        ->expectsQuestion('Bag class name', 'MyBag12')
        ->expectsQuestion('Namespace', 'App\Test')
        ->expectsOutputToContain('Bag [app/Test/MyBag12.php] created successfully.')
        ->expectsOutputToContain('Bag Factory [app/Test/Factories/TestFactory.php] created successfully.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Test/MyBag12.php')))->toBe(
        <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test;
            
            use App\Test\Factories\TestFactory;
            use Bag\Attributes\Factory;
            use Bag\Bag;
            use Bag\Traits\HasFactory;
            
            #[Factory(TestFactory::class)]
            readonly class MyBag12 extends Bag
            {
                use HasFactory;
            
                public function __construct()
                {
                }
            }
            
            EOF
    )
        ->and(file_get_contents(app_path('Test/Factories/TestFactory.php')))->toBe(
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
            
            EOF
        );

});

test('it accepts collection arg value', function () {
    $this->artisan('make:bag --collection=TestCollection')
        ->expectsQuestion('Bag class name', 'MyBag13')
        ->expectsQuestion('Namespace', 'App\Test')
        ->expectsOutputToContain('Bag [app/Test/MyBag13.php] created successfully.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Test/MyBag13.php')))->toBe(
        <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test;
            
            use App\Test\Collections\TestCollection;
            use Bag\Attributes\Collection;
            use Bag\Bag;
            
            #[Collection(TestCollection::class)]
            readonly class MyBag13 extends Bag
            {
                public function __construct()
                {
                }
            }
            
            EOF
    )
        ->and(file_get_contents(app_path('Test/Collections/TestCollection.php')))->toBe(
            <<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Test\Collections;
            
            use Bag\Collection;
            
            class TestCollection extends Collection
            {
            }
            
            EOF
        );

});

test('it updates', function () {
    $this->artisan('make:bag MyBag14 --namespace=App\\\\Values')
        ->assertExitCode(0);

    $this->artisan('make:bag MyBag14 --namespace=App\\\\Values --update --factory --collection')
        ->expectsQuestion('Factory class name', 'MyBag14Factory')
        ->expectsQuestion('Collection class name', 'MyBag14Collection')
        ->expectsOutputToContain('Bag [app/Values/MyBag14.php] updated successfully.')
        ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBag14Factory.php] created successfully.')
        ->expectsOutputToContain('Bag Collection [app/Values/Collections/MyBag14Collection.php] created successfully.')
        ->assertExitCode(0);
});

test('it generates factory', function () {
    if (!file_exists(app_path('Values'))) {
        mkdir(app_path('Values'), recursive: true);
    }

    file_put_contents(
        app_path('Values/MyBag15.php'),
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
            
            readonly class MyBag15 extends Bag
            {
                public function __construct(
                    public string $name,
                    public int $age,
                    #[Cast(DateTime::class, 'y-m-d')]
                    public CarbonImmutable $birthday,
                    public Money $money,
                    public MyBag15 $test,
                    #[Cast(CollectionOf::class, MyBag15::class)]
                    public Collection $collection,
                ) {
                }
            }
            
            EOF
    );

    $this->artisan('make:bag MyBag15 --namespace=App\\\\Values --update --factory=MyBag15Factory')
        ->expectsOutputToContain('Bag Factory [app/Values/Factories/MyBag15Factory.php] created successfully.')
        ->assertExitCode(0);

    expect(file_get_contents(app_path('Values/Factories/MyBag15Factory.php')))->toBe(<<<'EOF'
            <?php

            declare(strict_types=1);
            
            namespace App\Values\Factories;
            
            use App\Values\MyBag15;
            use Bag\Factory;
            use Brick\Money\Money;
            use Carbon\CarbonImmutable;
            
            class MyBag15Factory extends Factory
            {
                public function definition(): array
                {
                    return [
                        'name' => $this->faker->word(),
                        'age' => $this->faker->randomNumber(),
                        'birthday' => new CarbonImmutable(),
                        'money' => Money::ofMinor($this->faker->numberBetween(100, 10000), 'USD'),
                        'test' => MyBag15::empty(),
                        'collection' => MyBag15::collect([MyBag15::empty()]),
                    ];
                }
            }
            
            EOF);
});

beforeEach(function () {
    try {
        $dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(app_path('Values'), \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($dir as $filename => $fileInfo) {
            if ($fileInfo->isDir()) {
                rmdir($filename);
            } else {
                unlink($filename);
            }
        }

        $dir = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(app_path('Test'), \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($dir as $filename => $fileInfo) {
            if ($fileInfo->isDir()) {
                rmdir($filename);
            } else {
                unlink($filename);
            }
        }
    } catch (\UnexpectedValueException) {
    }
});
