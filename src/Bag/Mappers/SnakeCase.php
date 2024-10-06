<?php

declare(strict_types=1);

namespace Bag\Mappers;

readonly class SnakeCase extends Stringable
{
    public function __construct()
    {
        parent::__construct('snake');
    }
}
