<?php

declare(strict_types=1);

namespace Bag\Exceptions;

use Exception;
use Illuminate\Support\Collection;

class MissingPropertiesException extends Exception
{
    public function __construct(Collection $missingParameters)
    {
        parent::__construct('Missing required properties: '.$missingParameters->keys()->implode(', '));
    }
}
