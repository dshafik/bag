<?php

declare(strict_types=1);
use Bag\Attributes\Validation\Between;
use Bag\Attributes\Validation\Boolean;
use Bag\Attributes\Validation\Decimal;
use Bag\Attributes\Validation\Email;
use Bag\Attributes\Validation\Enum;
use Bag\Attributes\Validation\Exists;
use Bag\Attributes\Validation\In;
use Bag\Attributes\Validation\Integer;
use Bag\Attributes\Validation\Max;
use Bag\Attributes\Validation\Min;
use Bag\Attributes\Validation\NotRegex;
use Bag\Attributes\Validation\Numeric;
use Bag\Attributes\Validation\Regex;
use Bag\Attributes\Validation\Required;
use Bag\Attributes\Validation\RequiredIf;
use Bag\Attributes\Validation\RequiredUnless;
use Bag\Attributes\Validation\RequiredWith;
use Bag\Attributes\Validation\RequiredWithAll;
use Bag\Attributes\Validation\Rule;
use Bag\Attributes\Validation\Size;
use Bag\Attributes\Validation\Str;
use Bag\Attributes\Validation\Unique;
use Tests\Fixtures\Enums\TestBackedEnum;

covers(
    Between::class,
    Boolean::class,
    Decimal::class,
    Email::class,
    Enum::class,
    Exists::class,
    In::class,
    Integer::class,
    Max::class,
    Min::class,
    NotRegex::class,
    Numeric::class,
    Regex::class,
    Required::class,
    RequiredIf::class,
    RequiredUnless::class,
    RequiredWith::class,
    RequiredWithAll::class,
    Rule::class,
    Size::class,
    Str::class,
    Unique::class,
);

test('it creates rules', function (string $ruleAttribute, array $arguments, mixed $expectedRule) {
    /** @var Rule $ruleAttribute */
    $rule = new $ruleAttribute(...$arguments);

    expect($rule->rule)->toBe($expectedRule);
})->with('rules');

test('it creates enum rule', function () {
    $rule = new Enum(TestBackedEnum::class);
    expect((array) $rule->rule)->toBe((array) new \Illuminate\Validation\Rules\Enum(TestBackedEnum::class));
});

test('it creates custom rules', function () {
    $rule = new Rule('before', '2024-04-30');

    expect($rule->rule)->toBe('before:2024-04-30');
});

dataset('rules', function () {
    return [
        Between::class => [
            'ruleAttribute' => Between::class,
            'arguments' => [
                1,
                3,
            ],
            'expectedRule' => 'between:1,3',
        ],
        Boolean::class => [
            'ruleAttribute' => Boolean::class,
            'arguments' => [],
            'expectedRule' => 'boolean',
        ],
        Decimal::class => [
            'ruleAttribute' => Decimal::class,
            'arguments' => [
                2,
                5,
            ],
            'expectedRule' => 'decimal:2,5',
        ],
        Email::class => [
            'ruleAttribute' => Email::class,
            'arguments' => [
                true,
                true,
                true,
                true,
                true,
                true,
            ],
            'expectedRule' => 'email:rfc,strict,dns,spoof,filter,filterUnicode',
        ],
        'EmailDefaultsToRfc' => [
            'ruleAttribute' => Email::class,
            'arguments' => [
                false
            ],
            'expectedRule' => 'email:rfc',
        ],
        Exists::class => [
            'ruleAttribute' => Exists::class,
            'arguments' => [
                'test_models',
                'email',
            ],
            'expectedRule' => 'exists:test_models,email',
        ],
        In::class => [
            'ruleAttribute' => In::class,
            'arguments' => [
                'foo',
                'bar',
                'baz',
            ],
            'expectedRule' => 'in:foo,bar,baz',
        ],
        Integer::class => [
            'ruleAttribute' => Integer::class,
            'arguments' => [
            ],
            'expectedRule' => 'integer',
        ],
        Max::class => [
            'ruleAttribute' => Max::class,
            'arguments' => [
                3
            ],
            'expectedRule' => 'max:3',
        ],
        Min::class => [
            'ruleAttribute' => Min::class,
            'arguments' => [
                3
            ],
            'expectedRule' => 'min:3',
        ],
        NotRegex::class => [
            'ruleAttribute' => NotRegex::class,
            'arguments' => [
                '/foo/'
            ],
            'expectedRule' => 'not_regex:/foo/',
        ],
        Numeric::class => [
            'ruleAttribute' => Numeric::class,
            'arguments' => [
            ],
            'expectedRule' => 'numeric',
        ],
        Regex::class => [
            'ruleAttribute' => Regex::class,
            'arguments' => [
                '/foo/'
            ],
            'expectedRule' => 'regex:/foo/',
        ],
        Required::class => [
            'ruleAttribute' => Required::class,
            'arguments' => [
            ],
            'expectedRule' => 'required',
        ],
        RequiredIf::class => [
            'ruleAttribute' => RequiredIf::class,
            'arguments' => [
                'foo',
                'bar',
            ],
            'expectedRule' => 'required_if:foo,bar',
        ],
        RequiredUnless::class => [
            'ruleAttribute' => RequiredUnless::class,
            'arguments' => [
                'foo',
                'bar',
            ],
            'expectedRule' => 'required_unless:foo,bar',
        ],
        RequiredWith::class => [
            'ruleAttribute' => RequiredWith::class,
            'arguments' => [
                'foo'
            ],
            'expectedRule' => 'required_with:foo',
        ],
        RequiredWithAll::class => [
            'ruleAttribute' => RequiredWithAll::class,
            'arguments' => [
                'foo',
                'bar',
                'baz',
            ],
            'expectedRule' => 'required_with_all:foo,bar,baz',
        ],
        Size::class => [
            'ruleAttribute' => Size::class,
            'arguments' => [
                3
            ],
            'expectedRule' => 'size:3',
        ],
        Str::class => [
            'ruleAttribute' => Str::class,
            'arguments' => [
            ],
            'expectedRule' => 'string',
        ],
        Unique::class => [
            'ruleAttribute' => Unique::class,
            'arguments' => [
                'test_models',
                'email',
            ],
            'expectedRule' => 'unique:test_models,email',
        ],
    ];
});
