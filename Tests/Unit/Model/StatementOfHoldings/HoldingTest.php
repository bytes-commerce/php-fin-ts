<?php

namespace Fhp\Tests\Unit\Model\StatementOfHoldings;

use Fhp\Model\StatementOfHoldings\Holding;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for Holding model class.
 * Tests all getter/setter pairs for financial holdings.
 */
class HoldingTest extends TestCase
{
    private Holding $holding;

    protected function setUp(): void
    {
        $this->holding = new Holding();
    }

    public function testSetAndGetISIN(): void
    {
        $isin = 'DE0008404005';
        $this->holding->setISIN($isin);
        $this->assertSame($isin, $this->holding->getISIN());
    }

    public function testSetISINReturnsThis(): void
    {
        $result = $this->holding->setISIN('DE0008404005');
        $this->assertSame($this->holding, $result);
    }

    public function testISINInitiallyNull(): void
    {
        $this->assertNull($this->holding->getISIN());
    }

    public function testSetAndGetWKN(): void
    {
        $wkn = '840400';
        $this->holding->setWKN($wkn);
        $this->assertSame($wkn, $this->holding->getWKN());
    }

    public function testSetWKNReturnsThis(): void
    {
        $result = $this->holding->setWKN('840400');
        $this->assertSame($this->holding, $result);
    }

    public function testWKNInitiallyNull(): void
    {
        $this->assertNull($this->holding->getWKN());
    }

    public function testSetAndGetName(): void
    {
        $name = 'Allianz SE';
        $this->holding->setName($name);
        $this->assertSame($name, $this->holding->getName());
    }

    public function testSetNameReturnsThis(): void
    {
        $result = $this->holding->setName('Allianz SE');
        $this->assertSame($this->holding, $result);
    }

    public function testNameInitiallyNull(): void
    {
        $this->assertNull($this->holding->getName());
    }

    public function testSetAndGetValue(): void
    {
        $value = 1234.56;
        $this->holding->setValue($value);
        $this->assertSame($value, $this->holding->getValue());
    }

    public function testSetValueReturnsThis(): void
    {
        $result = $this->holding->setValue(1234.56);
        $this->assertSame($this->holding, $result);
    }

    public function testValueInitiallyNull(): void
    {
        $this->assertNull($this->holding->getValue());
    }

    public function testSetAndGetPrice(): void
    {
        $price = 234.50;
        $this->holding->setPrice($price);
        $this->assertSame($price, $this->holding->getPrice());
    }

    public function testSetPriceReturnsThis(): void
    {
        $result = $this->holding->setPrice(234.50);
        $this->assertSame($this->holding, $result);
    }

    public function testPriceInitiallyNull(): void
    {
        $this->assertNull($this->holding->getPrice());
    }

    public function testSetAndGetAcquisitionPrice(): void
    {
        $price = 200.00;
        $this->holding->setAcquisitionPrice($price);
        $this->assertSame($price, $this->holding->getAcquisitionPrice());
    }

    public function testSetAcquisitionPriceReturnsThis(): void
    {
        $result = $this->holding->setAcquisitionPrice(200.00);
        $this->assertSame($this->holding, $result);
    }

    public function testAcquisitionPriceInitiallyNull(): void
    {
        $this->assertNull($this->holding->getAcquisitionPrice());
    }

    public function testSetAndGetAmount(): void
    {
        $amount = 10.0;
        $this->holding->setAmount($amount);
        $this->assertSame($amount, $this->holding->getAmount());
    }

    public function testSetAmountReturnsThis(): void
    {
        $result = $this->holding->setAmount(10.0);
        $this->assertSame($this->holding, $result);
    }

    public function testAmountInitiallyNull(): void
    {
        $this->assertNull($this->holding->getAmount());
    }

    public function testSetAndGetCurrency(): void
    {
        $currency = 'EUR';
        $this->holding->setCurrency($currency);
        $this->assertSame($currency, $this->holding->getCurrency());
    }

    public function testSetCurrencyReturnsThis(): void
    {
        $result = $this->holding->setCurrency('EUR');
        $this->assertSame($this->holding, $result);
    }

    public function testCurrencyInitiallyNull(): void
    {
        $this->assertNull($this->holding->getCurrency());
    }

    public function testSetAndGetDate(): void
    {
        $date = new \DateTime('2024-01-15');
        $this->holding->setDate($date);
        $this->assertSame($date, $this->holding->getDate());
    }

    public function testSetDateReturnsThis(): void
    {
        $result = $this->holding->setDate(new \DateTime('2024-01-15'));
        $this->assertSame($this->holding, $result);
    }

    public function testDateInitiallyNull(): void
    {
        $this->assertNull($this->holding->getDate());
    }

    public function testSetAndGetTime(): void
    {
        $time = new \DateTime('2024-01-15 14:30:00');
        $this->holding->setTime($time);
        $this->assertSame($time, $this->holding->getTime());
    }

    public function testSetTimeReturnsThis(): void
    {
        $result = $this->holding->setTime(new \DateTime('2024-01-15 14:30:00'));
        $this->assertSame($this->holding, $result);
    }

    public function testTimeInitiallyNull(): void
    {
        $this->assertNull($this->holding->getTime());
    }

    public function testFluentInterface(): void
    {
        $date = new \DateTime('2024-01-15');
        $time = new \DateTime('2024-01-15 14:30:00');

        $result = $this->holding
            ->setISIN('DE0008404005')
            ->setWKN('840400')
            ->setName('Allianz SE')
            ->setValue(1234.56)
            ->setPrice(234.50)
            ->setAcquisitionPrice(200.00)
            ->setAmount(10.0)
            ->setCurrency('EUR')
            ->setDate($date)
            ->setTime($time);

        $this->assertSame($this->holding, $result);
        $this->assertSame('DE0008404005', $this->holding->getISIN());
        $this->assertSame('840400', $this->holding->getWKN());
        $this->assertSame('Allianz SE', $this->holding->getName());
        $this->assertSame(1234.56, $this->holding->getValue());
        $this->assertSame(234.50, $this->holding->getPrice());
        $this->assertSame(200.00, $this->holding->getAcquisitionPrice());
        $this->assertSame(10.0, $this->holding->getAmount());
        $this->assertSame('EUR', $this->holding->getCurrency());
        $this->assertSame($date, $this->holding->getDate());
        $this->assertSame($time, $this->holding->getTime());
    }

    public function testSetNullValues(): void
    {
        $this->holding->setISIN('DE0008404005');
        $this->holding->setISIN(null);
        $this->assertNull($this->holding->getISIN());

        $this->holding->setWKN('840400');
        $this->holding->setWKN(null);
        $this->assertNull($this->holding->getWKN());

        $this->holding->setName('Allianz SE');
        $this->holding->setName(null);
        $this->assertNull($this->holding->getName());
    }

    public function testSetZeroValues(): void
    {
        $this->holding->setValue(0.0);
        $this->assertSame(0.0, $this->holding->getValue());

        $this->holding->setPrice(0.0);
        $this->assertSame(0.0, $this->holding->getPrice());

        $this->holding->setAcquisitionPrice(0.0);
        $this->assertSame(0.0, $this->holding->getAcquisitionPrice());

        $this->holding->setAmount(0.0);
        $this->assertSame(0.0, $this->holding->getAmount());
    }

    public function testSetNegativeValues(): void
    {
        $this->holding->setValue(-100.50);
        $this->assertSame(-100.50, $this->holding->getValue());

        $this->holding->setPrice(-50.25);
        $this->assertSame(-50.25, $this->holding->getPrice());
    }
}
