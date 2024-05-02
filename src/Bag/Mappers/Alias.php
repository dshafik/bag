<?php

declare(strict_types=1);

namespace Bag\Mappers;

class Alias implements MapperInterface
{
    public function __construct(protected string $alias)
    {
    }

    public function __invoke(string $inputName): string
    {
        return $this->alias;
    }
}
