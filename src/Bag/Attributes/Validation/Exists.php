<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;
use Illuminate\Database\Eloquent\Model;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Exists extends Rule
{
    /**
     * @param string|class-string<Model> $table
     */
    public function __construct(string $table, string $column = null)
    {
        parent::__construct('exists', $table, $column);
    }
}
