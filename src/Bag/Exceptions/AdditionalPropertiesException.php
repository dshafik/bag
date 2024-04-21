<?php

declare(strict_types=1);

namespace Bag\Exceptions;

use Exception;
use Illuminate\Support\Collection;

class AdditionalPropertiesException extends Exception
{
    public function __construct(Collection $extraProperties)
    {
        parent::__construct('Additional properties found: '.$extraProperties->keys()->implode(', '));
    }
}
