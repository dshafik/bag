<?php

declare(strict_types=1);

namespace Tests\Feature\Concerns;

use Bag\Collection;
use Bag\Concerns\WithEloquentCasting;
use Bag\Eloquent\Casts\AsBagCollection;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\TestCase;
use function Orchestra\Testbench\workbench_path;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Models\CastedModel;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\Fixtures\Values\HiddenPropertiesBag;
use Tests\Fixtures\Values\TestBag;

#[WithEnv('DB_CONNECTION', 'testing')]
#[CoversClass(WithEloquentCasting::class)]
#[CoversClass(AsBagCollection::class)]
class WithEloquentCastingTest extends TestCase
{
    use DatabaseTransactions;

    public function testItStoresBag()
    {
        CastedModel::create([
            'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        $this->assertDatabaseHas('testing', ['bag' => '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}']);
    }

    public function testItDoesNotStoreNull()
    {
        CastedModel::create([
            'bag' => null,
        ]);

        $this->assertDatabaseHas('testing', ['bag' => null]);
    }

    public function testItRetrievesBag()
    {
        CastedModel::create([
            'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        $this->assertInstanceOf(TestBag::class, $model->bag);
        $this->assertSame('Davey Shafik', $model->bag->name);
        $this->assertSame(40, $model->bag->age);
        $this->assertSame('davey@php.net', $model->bag->email);
        $this->assertNull($model->collection);
    }

    public function testItDoesNotRetrieveNullBag()
    {
        CastedModel::create([
            'bag' => null,
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        $this->assertNull($model->bag);
    }

    public function testItStoresCollection()
    {
        CastedModel::create([
            'collection' => [
                TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
                TestBag::from(['name' => 'Example Person', 'age' => 39, 'email' => 'testing@example.org']),
            ]
        ]);

        $this->assertDatabaseHas('testing', ['collection' => '[{"name":"Davey Shafik","age":40,"email":"davey@php.net"},{"name":"Example Person","age":39,"email":"testing@example.org"}]']);
    }

    public function testItDoesNotStoreNullCollection()
    {
        CastedModel::create([
            'collection' => null
        ]);

        $this->assertDatabaseHas('testing', ['collection' => null]);
    }

    public function testItRetrievesCollection()
    {
        CastedModel::create([
            'collection' => [
                TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
                TestBag::from(['name' => 'Example Person', 'age' => 39, 'email' => 'testing@example.org']),
            ]
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        $this->assertInstanceOf(Collection::class, $model->collection);
        $this->assertCount(2, $model->collection);
        $this->assertContainsOnlyInstancesOf(TestBag::class, $model->collection);
        $this->assertSame('Davey Shafik', $model->collection[0]->name);
        $this->assertSame(40, $model->collection[0]->age);
        $this->assertSame('davey@php.net', $model->collection[0]->email);
        $this->assertSame('Example Person', $model->collection[1]->name);
        $this->assertSame(39, $model->collection[1]->age);
        $this->assertSame('testing@example.org', $model->collection[1]->email);
    }

    public function testItDoesNotRetrieveNullCollection()
    {
        CastedModel::create([
            'collection' => null
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        $this->assertNull($model->collection);
    }

    public function testItStoresCustomCollection()
    {
        CastedModel::create([
            'custom_collection' => BagWithCollection::collect([
                BagWithCollection::from(['name' => 'Davey Shafik', 'age' => 40]),
            ]),
        ]);

        $this->assertDatabaseHas('testing', ['custom_collection' => '[{"name":"Davey Shafik","age":40}]']);
    }

    public function testItRetrievesCustomCollection()
    {
        CastedModel::create([
            'custom_collection' => BagWithCollection::collect([
                BagWithCollection::from(['name' => 'Davey Shafik', 'age' => 40]),
            ]),
        ]);

        $model = CastedModel::first();

        $this->assertInstanceOf(BagWithCollectionCollection::class, $model->custom_collection);
        $this->assertCount(1, $model->custom_collection);
        $this->assertContainsOnlyInstancesOf(BagWithCollection::class, $model->custom_collection);
    }

    public function testItStoresHiddenProperties()
    {
        CastedModel::create([
            'hidden_bag' => HiddenPropertiesBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        $this->assertDatabaseHas('testing', ['hidden_bag' => '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}']);
    }

    public function testItRetrievesHiddenProperties()
    {
        CastedModel::create([
            'hidden_bag' => HiddenPropertiesBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        $model = CastedModel::first();

        $this->assertInstanceOf(HiddenPropertiesBag::class, $model->hidden_bag);
        $this->assertSame('Davey Shafik', $model->hidden_bag->name);
        $this->assertSame(40, $model->hidden_bag->age);
        $this->assertSame('davey@php.net', $model->hidden_bag->email);
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(workbench_path('database/migrations'));
    }
}
