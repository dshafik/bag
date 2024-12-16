<?php

declare(strict_types=1);

namespace Bag\Attributes;

use Attribute;
use Bag\Attributes\Attribute as AttributeInterface;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class Bag implements AttributeInterface
{
    public const PUBLIC = 1;
    public const PROTECTED = 2;
    public const PRIVATE = 4;
    public const ALL = 7;

    /**
     * @param class-string<\Bag\Bag> $bagClass
     */
    public function __construct(
        public string $bagClass,
        public int $visibility = self::PUBLIC,
    ) {
    }
}
