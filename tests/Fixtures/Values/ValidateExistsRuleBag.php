<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;

readonly class ValidateExistsRuleBag extends Bag
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
            'name' => ['required', 'string', 'exists:test_models'],
            'age' => ['required', 'integer'],
        ];
    }
}
