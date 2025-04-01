<?php

declare(strict_types=1);

use Bag\TypeScript\BagTransformer;
use Bag\TypeScript\Reflection\BagReflectionProperty;
use Bag\TypeScript\Reflection\BagReflectionUnionType;
use Spatie\TypeScriptTransformer\TypeScriptTransformerConfig;
use Tests\Fixtures\Values\TypeScriptBag;

beforeEach()->skip(!class_exists(TypeScriptTransformerConfig::class));

if (class_exists(TypeScriptTransformerConfig::class)) {
    covers(BagTransformer::class);
}
covers(BagReflectionProperty::class, BagReflectionUnionType::class);

test('it transforms bags to typescript', function () {
    $config = TypeScriptTransformerConfig::create();
    $transformer = new BagTransformer($config);

    $type = $transformer->transform(new \ReflectionClass(TypeScriptBag::class), 'Typed');

    expect($type->transformed)->toBe(
        <<<TYPESCRIPT
        {
        name: string;
        age?: number;
        email_address?: string | null;
        }
        TYPESCRIPT
    );
});
