<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Validation\Max;
use Bag\Attributes\Validation\Required;
use Bag\Bag;

readonly class ValidateUsingAttributesAndRulesMethodBag extends Bag
{
    public function __construct(
        #[Required]
        #[Max(100)]
        public string $name,
        #[Max(100)]
        public int $age,
    ) {
    }

    public static function rules(): array
    {
        return [
            'name' => ['string'],
            'age' => ['integer'],
        ];
    }
}
