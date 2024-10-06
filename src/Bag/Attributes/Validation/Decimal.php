<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
readonly class Decimal extends Rule implements AttributeInterface
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
