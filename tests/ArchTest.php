<?php

declare(strict_types=1);

arch('it does not call debug methods')
    ->expect('dd')->not->toBeUsed()
    ->and('ddd')->not->toBeUsed()
    ->and('dump')->not->toBeUsed()
    ->and('xdebug_break')->not->toBeUsed();

arch('it meets PHP preset')
    ->skip(fn () => !version_compare(Pest\version(), '3.0.0', '>='), 'Requires Pest 3+')
    ->preset()
    ->php()
    ->ignoring('var_export');

arch('it has strict types')
    ->expect('Bag')
    ->toUseStrictTypes();

arch('it uses strict equality')
    ->expect('Bag')
    ->toUseStrictEquality();

arch('it does not use final')
    ->expect('Bag')
    ->classes()
    ->not->toBeFinal();

arch('it has readonly pipelines')
    ->expect('Bag\Pipelines')
    ->classes()
    ->toBeReadonly()
    ->ignoring('Bag\Pipelines\Values');

arch('it has readonly casts')
    ->expect('Bag\Casts')
    ->classes()
    ->toBeReadonly();

arch('it has readonly attributes')
    ->expect('Bag\Attributes')
    ->classes()
    ->toBeReadonly();

arch('it has readonly mappers')
    ->expect('Bag\Mappers')
    ->classes()
    ->toBeReadonly();

arch('that concerns are traits')
    ->expect('Bag\Concerns')
    ->toBeTraits();

arch('that traits are traits')
    ->expect('Bag\Traits')
    ->toBeTraits();

arch('that enums are enums')
    ->expect('Bag\Enums')
    ->toBeEnums();

arch('that exceptions extend Exception')
    ->expect('Bag\Exceptions')
    ->classes()
    ->toExtend(\Exception::class);

arch('it has no private methods')
    ->expect('Bag')
    ->not->toHavePrivateMethods();
