<?php

namespace Tests\Fixtures\Factories;

use Bag\Factory;
use Illuminate\Support\Collection as LaravelCollection;

class BagWithFactoryFactory extends Factory
{

    #[\Override]
    public function definition(): LaravelCollection|array
    {
        return LaravelCollection::make([
            'name' => 'Davey Shafik',
            'age' => 40,
        ]);
    }
}
