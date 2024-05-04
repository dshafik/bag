<?php

declare(strict_types=1);

namespace Tests\Unit\Casts;

use Bag\Casts\MoneyFromMinor;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use PHPUnit\Framework\Attributes\CoversClass;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Tests\Fixtures\Enums\TestCurrencyEnum;
use Tests\TestCase;

#[CoversClass(MoneyFromMinor::class)]
class MoneyFromMinorTest extends TestCase
{
    public function testItDoesNotCastMoney()
    {
        $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar);

        $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => Money::of(100, 'CAD')]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'CAD')));
    }

    public function testItCastsMoneyWithBackedEnumCurrency()
    {
        $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar);

        $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithStringCurrency()
    {
        $cast = new MoneyFromMinor(currency: 'USD');

        $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithCurrencyPropertyAsString()
    {
        $cast = new MoneyFromMinor(currencyProperty: 'currency');

        $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => 'USD']));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithCurrencyPropertyAsBackedEnum()
    {
        $cast = new MoneyFromMinor(currencyProperty: 'currency');

        $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => CurrencyAlpha3::US_Dollar]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithCurrencyPropertyAsUnitEnum()
    {
        $cast = new MoneyFromMinor(currencyProperty: 'currency');

        $money =  $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => TestCurrencyEnum::USD]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItFailsWithNoCurrency()
    {
        $this->expectException(UnknownCurrencyException::class);
        $this->expectExceptionMessage('No currency found');

        $cast = new MoneyFromMinor(currencyProperty: 'currency');
        $cast->set(MoneyFromMinor::class, 'test', collect(['test' => 10000, 'currency' => null]));
    }

    public function testItFormatsOutput()
    {
        $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar);
        $this->assertSame('$100.00', $cast->get('test', collect(['test' => Money::of(100, 'USD')])));

        $cast = new MoneyFromMinor(currency: CurrencyAlpha3::US_Dollar, locale: 'en_GB');
        $this->assertSame('US$100.00', $cast->get('test', collect(['test' => Money::of(100, 'USD')])));
    }
}
