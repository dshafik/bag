<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Bag;
use Bag\Validation\Rules\OptionalOr;
use Bag\Values\Optional;

readonly class OptionalBagWithValidation extends Bag
{
    public function __construct(
        public string $name,
        public Optional|int $age,
        public Optional|string $email,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'age' => ['required', 'integer'], // Regular validator, not OptionalOr
            'email' => [new OptionalOr('email')], // OptionalOr validator
        ];
    }
}
