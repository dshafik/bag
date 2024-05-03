<?php

declare(strict_types=1);

namespace Bag\Exceptions;

use Exception;

class BagNotFoundException extends Exception
{
    public function __construct(string $bagClass)
    {
        parent::__construct(sprintf('The Bag class "%s" does not exist', $bagClass));
    }
}
