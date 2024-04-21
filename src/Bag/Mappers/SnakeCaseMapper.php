<?php

declare(strict_types=1);

namespace Bag\Mappers;

class SnakeCaseMapper extends StringableMapper
{
    public function __construct()
    {
        $this->stringOperations = ['snake'];
    }
}
