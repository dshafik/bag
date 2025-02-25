<?php

declare(strict_types=1);

namespace Bag\Exceptions;

use Bag\Property\ValueCollection;
use Exception;

class MissingPropertiesException extends Exception
{
    public function __construct(string $bagClassname, ValueCollection $missingParameters)
    {
        parent::__construct(sprintf(
            'Missing required properties for Bag %s: %s',
            $bagClassname,
            $missingParameters->keys()->implode(', ')
        ));
    }
}
