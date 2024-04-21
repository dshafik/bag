<?php

declare(strict_types=1);

namespace Bag\Mappers;

interface MapperInterface
{
    public function __invoke(string $inputName): string;
}
