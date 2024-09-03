<?php

declare(strict_types=1);

namespace Bag\Exceptions;

use Exception;
use Illuminate\Support\Collection;

class MissingPropertiesException extends Exception
{
    public function __construct(string $bagClassname, Collection $missingParameters)
    {
        parent::__construct(sprintf(
            'Missing required properties for Bag %s: %s',
            $bagClassname,
            $missingParameters->keys()->implode(', ')
        ));
    }
}
