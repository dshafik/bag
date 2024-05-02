<?php

declare(strict_types=1);

namespace Bag\Mappers;

class CamelCase extends Stringable
{
    public function __construct()
    {
        parent::__construct('camel');
    }
}
