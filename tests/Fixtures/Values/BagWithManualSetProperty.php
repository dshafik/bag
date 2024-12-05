<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class BagWithManualSetProperty extends Bag
{
    public string $firstName;

    public string $lastName;

    public function __construct(
        public string $fullName,
    ) {
        $nameParts = explode(' ', $fullName);
        $this->firstName = $nameParts[0];
        $this->lastName = $nameParts[1] ?? '';
    }
}
