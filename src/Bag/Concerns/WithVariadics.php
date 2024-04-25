<?php

declare(strict_types=1);

namespace Bag\Concerns;

use Bag\Attributes\Variadic;
use Bag\Property\ValueCollection;
use ReflectionClass;

trait WithVariadics
{
    protected static function variadic(ReflectionClass $class, ValueCollection $properties): array
    {
        $variadics = $class->getAttributes(Variadic::class);
        /** @var Variadic $variadic */
        $variadic = isset($variadics[0]) ? $variadics[0]->newInstance() : null;

        $isVariadic = false;
        if ($variadic !== null || $properties->last()->variadic) {
            $isVariadic = true;
        }
        return array($variadic, $isVariadic);
    }
}
