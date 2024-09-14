<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class ValidateUniqueRuleBag extends Bag
{
    public function __construct(
        public string $name,
        public int $age,
        public string $email,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:test_models'],
            'age' => ['required', 'integer'],
        ];
    }
}
