<?php

declare(strict_types=1);

namespace Tests\Fixtures\Collections;

use Bag\Attributes\Wrap;
use Bag\Attributes\WrapJson;
use Bag\Collection;

#[Wrap('collection_wrapper')]
#[WrapJson('collection_json_wrapper')]
class WrappedCollection extends Collection
{
}
