<?php

declare(strict_types=1);
use Bag\Casts\MoneyFromMinor;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Tests\Fixtures\Enums\TestCurrencyEnum;

test('it does not cast money', function () {
    $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar);

    $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => Money::of(100, 'CAD')]));

    /** @var Money $money */
    expect($money->isEqualTo(Money::of(100, 'CAD')))->toBeTrue();
});

test('it casts money with backed enum currency', function () {
    $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar);

    $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000]));

    /** @var Money $money */
    expect($money->isEqualTo(Money::of(100, 'USD')))->toBeTrue();
});

test('it casts money with string currency', function () {
    $cast = new MoneyFromMinor(currency: 'USD');

    $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000]));

    /** @var Money $money */
    expect($money->isEqualTo(Money::of(100, 'USD')))->toBeTrue();
});

test('it casts money with currency property as string', function () {
    $cast = new MoneyFromMinor(currencyProperty: 'currency');

    $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => 'USD']));

    /** @var Money $money */
    expect($money->isEqualTo(Money::of(100, 'USD')))->toBeTrue();
});

test('it casts money with currency property as backed enum', function () {
    $cast = new MoneyFromMinor(currencyProperty: 'currency');

    $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => CurrencyAlpha3::US_Dollar]));

    /** @var Money $money */
    expect($money->isEqualTo(Money::of(100, 'USD')))->toBeTrue();
});

test('it casts money with currency property as unit enum', function () {
    $cast = new MoneyFromMinor(currencyProperty: 'currency');

    $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => TestCurrencyEnum::USD]));

    /** @var Money $money */
    expect($money->isEqualTo(Money::of(100, 'USD')))->toBeTrue();
});

test('it fails with no currency', function () {
    $this->expectException(UnknownCurrencyException::class);
    $this->expectExceptionMessage('No currency found');

    $cast = new MoneyFromMinor(currencyProperty: 'currency');
    $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => null]));
});

test('it formats output', function () {
    $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar);
    expect($cast->get('test', collect(['test' => Money::of(100, 'USD')])))->toBe('$100.00');

    $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar, locale: 'en_GB');
    expect($cast->get('test', collect(['test' => Money::of(100, 'USD')])))->toBe('US$100.00');
});
