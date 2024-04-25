<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Rule
{
    public string|object $rule;

    public function __construct(
        string $rule,
        mixed ...$arguments,
    ) {
        if (\str_contains($rule, '\\')) {
            $this->rule = new $rule(...$arguments);

            return;
        }

        if (\count($arguments) === 0) {
            return;
        }

        $this->rule = $rule . ':'.\implode(',', $arguments);
    }
}
