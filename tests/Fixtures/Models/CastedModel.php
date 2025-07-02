<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Bag\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\Fixtures\Values\HiddenParametersBag;
use Tests\Fixtures\Values\NullableWithDefaultValueBag;
use Tests\Fixtures\Values\OptionalValueBag;
use Tests\Fixtures\Values\TestBag;

/**
 * @property int $id
 * @property TestBag $bag
 * @property NullableWithDefaultValueBag $nulls_bag
 * @property HiddenParametersBag $hidden_bag
 * @property Collection<TestBag> $collection
 * @property BagWithCollectionCollection $custom_collection
 */
class CastedModel extends Model
{
    protected $table = 'testing';

    protected $fillable = ['bag', 'nulls_bag', 'hidden_bag', 'collection', 'custom_collection', 'optional_bag'];

    protected function casts()
    {
        return [
            'bag' => TestBag::class,
            'nulls_bag' => NullableWithDefaultValueBag::class,
            'hidden_bag' => HiddenParametersBag::class,
            'optional_bag' => OptionalValueBag::class,
            'collection' => TestBag::castAsCollection(),
            'custom_collection' => BagWithCollection::castAsCollection(),
        ];
    }
}
