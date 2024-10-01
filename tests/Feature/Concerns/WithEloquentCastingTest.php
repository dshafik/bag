<?php

declare(strict_types=1);
use Bag\Collection;
use Bag\Concerns\WithEloquentCasting;
use Bag\Eloquent\Casts\AsBag;
use Bag\Eloquent\Casts\AsBagCollection;
use Illuminate\Foundation\Application;
use function Pest\Laravel\assertDatabaseHas;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Models\CastedModel;
use Tests\Fixtures\Models\CastedModelLegacy;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\Fixtures\Values\HiddenParametersBag;
use Tests\Fixtures\Values\TestBag;

covers(WithEloquentCasting::class, AsBag::class, AsBagCollection::class);

describe('Laravel 11+', function () {
    test('it stores bag on Laravel 11+', function () {
        CastedModel::create([
            'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        assertDatabaseHas('testing', ['bag' => '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}']);
    });

    test('it does not store null on Laravel 11+', function () {
        CastedModel::create([
            'bag' => null,
        ]);

        assertDatabaseHas('testing', ['bag' => null]);
    });

    test('it retrieves bag on Laravel 11+', function () {
        CastedModel::create([
            'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        expect($model->bag)->toBeInstanceOf(TestBag::class)
            ->and($model->bag->name)->toBe('Davey Shafik')
            ->and($model->bag->age)->toBe(40)
            ->and($model->bag->email)->toBe('davey@php.net')
            ->and($model->collection)->toBeNull();
    });

    test('it does not retrieve null bag on Laravel 11+', function () {
        CastedModel::create([
            'bag' => null,
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        expect($model->bag)->toBeNull();
    });

    test('it stores collection on Laravel 11+', function () {
        CastedModel::create([
            'collection' => [
                TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
                TestBag::from(['name' => 'Example Person', 'age' => 39, 'email' => 'testing@example.org']),
            ]
        ]);

        assertDatabaseHas('testing', ['collection' => '[{"name":"Davey Shafik","age":40,"email":"davey@php.net"},{"name":"Example Person","age":39,"email":"testing@example.org"}]']);
    });

    test('it does not store null collection on Laravel 11+', function () {
        CastedModel::create([
            'collection' => null
        ]);

        assertDatabaseHas('testing', ['collection' => null]);
    });

    test('it retrieves collection on Laravel 11+', function () {
        CastedModel::create([
            'collection' => [
                TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
                TestBag::from(['name' => 'Example Person', 'age' => 39, 'email' => 'testing@example.org']),
            ]
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        expect($model->collection)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(2)
            ->toContainOnlyInstancesOf(TestBag::class)
            ->and($model->collection[0]->name)->toBe('Davey Shafik')
            ->and($model->collection[0]->age)->toBe(40)
            ->and($model->collection[0]->email)->toBe('davey@php.net')
            ->and($model->collection[1]->name)->toBe('Example Person')
            ->and($model->collection[1]->age)->toBe(39)
            ->and($model->collection[1]->email)->toBe('testing@example.org');

    });

    test('it does not retrieve null collection on Laravel 11+', function () {
        CastedModel::create([
            'collection' => null
        ]);

        /** @var CastedModel $model */
        $model = CastedModel::first();

        expect($model->collection)->toBeNull();
    });

    test('it stores custom collection on Laravel 11+', function () {
        CastedModel::create([
            'custom_collection' => BagWithCollection::collect([
                BagWithCollection::from(['name' => 'Davey Shafik', 'age' => 40]),
            ]),
        ]);

        assertDatabaseHas('testing', ['custom_collection' => '[{"name":"Davey Shafik","age":40}]']);
    });

    test('it retrieves custom collection on Laravel 11+', function () {
        CastedModel::create([
            'custom_collection' => BagWithCollection::collect([
                BagWithCollection::from(['name' => 'Davey Shafik', 'age' => 40]),
            ]),
        ]);

        $model = CastedModel::first();

        expect($model->custom_collection)
            ->toBeInstanceOf(BagWithCollectionCollection::class)
            ->toHaveCount(1)
            ->toContainOnlyInstancesOf(BagWithCollection::class);
    });

    test('it stores hidden properties on Laravel 11+', function () {
        CastedModel::create([
            'hidden_bag' => HiddenParametersBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        assertDatabaseHas('testing', ['hidden_bag' => '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}']);
    });

    test('it retrieves hidden properties on Laravel 11+', function () {
        CastedModel::create([
            'hidden_bag' => HiddenParametersBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        $model = CastedModel::first();

        expect($model->hidden_bag)->toBeInstanceOf(HiddenParametersBag::class)
            ->and($model->hidden_bag->name)->toBe('Davey Shafik')
            ->and($model->hidden_bag->age)->toBe(40)
            ->and($model->hidden_bag->email)->toBe('davey@php.net');
    });
})->skip(fn () => !version_compare(Application::VERSION, '11.0.0', '>='), 'Requires Laravel 11+');

describe('Laravel 10+', function () {
    test('it stores bag on Laravel 10+', function () {
        CastedModelLegacy::create([
            'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        assertDatabaseHas('testing', ['bag' => '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}']);
    });

    test('it does not store null on Laravel 10+', function () {
        CastedModelLegacy::create([
            'bag' => null,
        ]);

        assertDatabaseHas('testing', ['bag' => null]);
    });

    test('it retrieves bag on Laravel 10+', function () {
        CastedModelLegacy::create([
            'bag' => TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        /** @var CastedModel $model */
        $model = CastedModelLegacy::first();

        expect($model->bag)->toBeInstanceOf(TestBag::class)
            ->and($model->bag->name)->toBe('Davey Shafik')
            ->and($model->bag->age)->toBe(40)
            ->and($model->bag->email)->toBe('davey@php.net')
            ->and($model->collection)->toBeNull();
    });

    test('it does not retrieve null bag on Laravel 10+', function () {
        CastedModelLegacy::create([
            'bag' => null,
        ]);

        /** @var CastedModel $model */
        $model = CastedModelLegacy::first();

        expect($model->bag)->toBeNull();
    });

    test('it stores collection on Laravel 10+', function () {
        CastedModelLegacy::create([
            'collection' => [
                TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
                TestBag::from(['name' => 'Example Person', 'age' => 39, 'email' => 'testing@example.org']),
            ]
        ]);

        assertDatabaseHas('testing', ['collection' => '[{"name":"Davey Shafik","age":40,"email":"davey@php.net"},{"name":"Example Person","age":39,"email":"testing@example.org"}]']);
    });

    test('it does not store null collection on Laravel 10+', function () {
        CastedModelLegacy::create([
            'collection' => null
        ]);

        assertDatabaseHas('testing', ['collection' => null]);
    });

    test('it retrieves collection on Laravel 10+', function () {
        CastedModelLegacy::create([
            'collection' => [
                TestBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
                TestBag::from(['name' => 'Example Person', 'age' => 39, 'email' => 'testing@example.org']),
            ]
        ]);

        /** @var CastedModel $model */
        $model = CastedModelLegacy::first();

        expect($model->collection)
            ->toBeInstanceOf(Collection::class)
            ->toHaveCount(2)
            ->toContainOnlyInstancesOf(TestBag::class)
            ->and($model->collection[0]->name)->toBe('Davey Shafik')
            ->and($model->collection[0]->age)->toBe(40)
            ->and($model->collection[0]->email)->toBe('davey@php.net')
            ->and($model->collection[1]->name)->toBe('Example Person')
            ->and($model->collection[1]->age)->toBe(39)
            ->and($model->collection[1]->email)->toBe('testing@example.org');

    });

    test('it does not retrieve null collection on Laravel 10+', function () {
        CastedModelLegacy::create([
            'collection' => null
        ]);

        /** @var CastedModel $model */
        $model = CastedModelLegacy::first();

        expect($model->collection)->toBeNull();
    });

    test('it stores custom collection on Laravel 10+', function () {
        CastedModelLegacy::create([
            'custom_collection' => BagWithCollection::collect([
                BagWithCollection::from(['name' => 'Davey Shafik', 'age' => 40]),
            ]),
        ]);

        assertDatabaseHas('testing', ['custom_collection' => '[{"name":"Davey Shafik","age":40}]']);
    });

    test('it retrieves custom collection on Laravel 10+', function () {
        CastedModelLegacy::create([
            'custom_collection' => BagWithCollection::collect([
                BagWithCollection::from(['name' => 'Davey Shafik', 'age' => 40]),
            ]),
        ]);

        $model = CastedModelLegacy::first();

        expect($model->custom_collection)
            ->toBeInstanceOf(BagWithCollectionCollection::class)
            ->toHaveCount(1)
            ->toContainOnlyInstancesOf(BagWithCollection::class);
    });

    test('it stores hidden properties on Laravel 10+', function () {
        CastedModelLegacy::create([
            'hidden_bag' => HiddenParametersBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        assertDatabaseHas('testing', ['hidden_bag' => '{"name":"Davey Shafik","age":40,"email":"davey@php.net"}']);
    });

    test('it retrieves hidden properties on Laravel 10+', function () {
        CastedModelLegacy::create([
            'hidden_bag' => HiddenParametersBag::from(['name' => 'Davey Shafik', 'age' => 40, 'email' => 'davey@php.net']),
        ]);

        $model = CastedModelLegacy::first();

        expect($model->hidden_bag)->toBeInstanceOf(HiddenParametersBag::class)
            ->and($model->hidden_bag->name)->toBe('Davey Shafik')
            ->and($model->hidden_bag->age)->toBe(40)
            ->and($model->hidden_bag->email)->toBe('davey@php.net');
    });
});
