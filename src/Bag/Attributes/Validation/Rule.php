<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Rule
{
    public function __construct(
        public string $rule,
        mixed ...$arguments,
    ) {
        if (\str_contains($this->rule, '\\')) {
            new $rule(...$arguments);

            return;
        }

        if (\count($arguments) === 0) {
            return;
        }

        $this->rule .= ':'.\implode(',', $arguments);
    }
}
