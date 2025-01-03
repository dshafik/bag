<?php

declare(strict_types=1);

namespace Tests\Fixtures\Values;

use Bag\Attributes\Validation\Max;
use Bag\Attributes\Validation\Required;
use Bag\Bag;

readonly class OptionalValidateUsingAttributesAndRulesMethodBag extends Bag
{
    public function __construct(
        #[Required]
        #[Max(100)]
        public ?string $name = null,
        #[Max(100)]
        public ?int $age = null,
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
