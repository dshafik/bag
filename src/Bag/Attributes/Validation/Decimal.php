<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Decimal extends Rule
{
    public function __construct(int $minPlaces, ?int $maxPlaces = null)
    {
        $places = [$minPlaces];
        if ($maxPlaces !== null) {
            $places[] = $maxPlaces;
        }

        parent::__construct('decimal', ...$places);
    }
}
