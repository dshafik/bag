<?php

declare(strict_types=1);

namespace Bag\Attributes\Validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Email extends Rule
{
    public function __construct(
        bool $rfc = true,
        bool $strict = false,
        bool $dns = false,
        bool $spoof = false,
        bool $filter = false,
        bool $filterUnicode = false
    ) {

        $validators = [];
        if ($rfc) {
            $validators[] = 'rfc';
        }

        if ($strict) {
            $validators[] = 'strict';
        }

        if ($dns) {
            $validators[] = 'dns';
        }

        if ($spoof) {
            $validators[] = 'spoof';
        }

        if ($filter) {
            $validators[] = 'filter';
        }

        if ($filterUnicode) {
            $validators[] = 'filterUnicode';
        }

        if (empty($validators)) {
            $validators[] = 'rfc';
        }

        parent::__construct('email', ...$validators);
    }
}
