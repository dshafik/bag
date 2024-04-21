<?php

declare(strict_types=1);

namespace Bag\Mappers;

class CamelCaseMapper extends StringableMapper
{
    public function __construct()
    {
        $this->stringOperations = ['camel'];
    }
}
