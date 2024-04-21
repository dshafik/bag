<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bag\Attributes\Validation\Required;
use Bag\Bag;

readonly class ValidateUsingAttributesAndRulesMethodBag extends Bag
{
    public function __construct(
        #[Required]
        public string $name,
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
