<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Bag\Collection;
use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\Collections\BagWithCollectionCollection;
use Tests\Fixtures\Values\BagWithCollection;
use Tests\Fixtures\Values\HiddenParametersBag;
use Tests\Fixtures\Values\TestBag;

/**
 * @property int $id
 * @property TestBag $bag
 * @property HiddenParametersBag $hidden_bag
 * @property Collection $collection
 * @property BagWithCollectionCollection $custom_collection
 */
class CastedModel extends Model
{
    protected $table = 'testing';

    protected $fillable = ['bag', 'hidden_bag', 'collection', 'custom_collection'];

    protected function casts()
    {
        return [
            'bag' => TestBag::class,
            'hidden_bag' => HiddenParametersBag::class,
            'collection' => TestBag::castAsCollection(),
            'custom_collection' => BagWithCollection::castAsCollection(),
        ];
    }
}
