<?php

declare(strict_types=1);

namespace Tests\Unit\Casts;

use Bag\Casts\MoneyFromMajor;
use Brick\Money\Exception\UnknownCurrencyException;
use Brick\Money\Money;
use PHPUnit\Framework\Attributes\CoversClass;
use PrinsFrank\Standards\Currency\CurrencyAlpha3;
use Tests\Fixtures\Enums\TestCurrencyEnum;
use Tests\TestCase;

#[CoversClass(MoneyFromMajor::class)]
class MoneyFromMajorTest extends TestCase
{
    public function testItDoesNotCastMoney()
    {
        $cast = new MoneyFromMajor(currency: CurrencyAlpha3::US_Dollar);

        $money =  $cast->set(MoneyFromMajor::class, 'test', collect(['test' => Money::ofMinor(10000, 'CAD')]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'CAD')));
    }

    public function testItCastsMoneyWithBackedEnumCurrency()
    {
        $cast = new MoneyFromMajor(currency: CurrencyAlpha3::US_Dollar);

        $money =  $cast->set(MoneyFromMajor::class, 'test', collect(['test' => 100]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithStringCurrency()
    {
        $cast = new MoneyFromMajor(currency: 'USD');

        $money =  $cast->set(MoneyFromMajor::class, 'test', collect(['test' => 100]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithCurrencyPropertyAsString()
    {
        $cast = new MoneyFromMajor(currencyProperty: 'currency');

        $money =  $cast->set(MoneyFromMajor::class, 'test', collect(['test' => 100, 'currency' => 'USD']));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithCurrencyPropertyAsBackedEnum()
    {
        $cast = new MoneyFromMajor(currencyProperty: 'currency');

        $money =  $cast->set(MoneyFromMajor::class, 'test', collect(['test' => 100, 'currency' => CurrencyAlpha3::US_Dollar]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItCastsMoneyWithCurrencyPropertyAsUnitEnum()
    {
        $cast = new MoneyFromMajor(currencyProperty: 'currency');

        $money =  $cast->set(MoneyFromMajor::class, 'test', collect(['test' => 100, 'currency' => TestCurrencyEnum::USD]));

        /** @var Money $money */
        $this->assertTrue($money->isEqualTo(Money::of(100, 'USD')));
    }

    public function testItFailsWithNoCurrency()
    {
        $this->expectException(UnknownCurrencyException::class);
        $this->expectExceptionMessage('No currency found');

        $cast = new MoneyFromMajor(currencyProperty: 'currency');
        $cast->set(MoneyFromMajor::class, 'test', collect(['test' => 100, 'currency' => null]));
    }

    public function testItFormatsOutput()
    {
        $cast = new MoneyFromMajor(currency: CurrencyAlpha3::US_Dollar);
        $this->assertSame('$100.00', $cast->get('test', collect(['test' => Money::of(100, 'USD')])));

        $cast = new MoneyFromMajor(currency: CurrencyAlpha3::US_Dollar, locale: 'en_GB');
        $this->assertSame('US$100.00', $cast->get('test', collect(['test' => Money::of(100, 'USD')])));
    }
}
