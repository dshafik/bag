<?php

declare(strict_types=1);

namespace Tests\Fixtures\Collections;

use Bag\Attributes\Wrap;
use Bag\Collection;

#[Wrap('collection_wrapper')]
class WrappedCollection extends Collection
{
}
