<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Bag;

readonly class ValidateUsingRulesMethodBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'age' => ['required', 'integer'],
        ];
    }
}
