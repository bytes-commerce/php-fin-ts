<?php

namespace Fhp\Tests\Unit\Segment\Common;

use Fhp\Segment\Common\Btg;
use Fhp\Segment\Common\Sdo;
use PHPUnit\Framework\TestCase;

final class SdoTest extends TestCase
{
    public function testCreateWithPositiveAmount(): void
    {
        $timestamp = new \DateTime('2024-01-15 14:30:00');
        $sdo = Sdo::create(100.50, 'EUR', $timestamp);

        $this->assertSame(Sdo::CREDIT, $sdo->sollHabenKennzeichen);
        $this->assertSame(100.50, $sdo->betrag->wert);
        $this->assertSame('EUR', $sdo->betrag->waehrung);
        $this->assertSame('20240115', $sdo->datum);
        $this->assertSame('143000', $sdo->uhrzeit);
    }

    public function testCreateWithNegativeAmount(): void
    {
        $timestamp = new \DateTime('2024-01-15 14:30:00');
        $sdo = Sdo::create(-50.25, 'USD', $timestamp);

        $this->assertSame(Sdo::DEBIT, $sdo->sollHabenKennzeichen);
        $this->assertSame(-50.25, $sdo->betrag->wert);
        $this->assertSame('USD', $sdo->betrag->waehrung);
    }

    public function testCreateWithMidnightTime(): void
    {
        $timestamp = new \DateTime('2024-01-15 00:00:00');
        $sdo = Sdo::create(100.00, 'EUR', $timestamp);

        $this->assertNull($sdo->uhrzeit);
    }

    public function testGetAmountCredit(): void
    {
        $sdo = new Sdo();
        $sdo->sollHabenKennzeichen = Sdo::CREDIT;
        $sdo->betrag = Btg::create(123.45, 'EUR');
        $sdo->datum = '20240115';

        $this->assertSame(123.45, $sdo->getAmount());
    }

    public function testGetAmountDebit(): void
    {
        $sdo = new Sdo();
        $sdo->sollHabenKennzeichen = Sdo::DEBIT;
        $sdo->betrag = Btg::create(123.45, 'EUR');
        $sdo->datum = '20240115';

        $this->assertSame(-123.45, $sdo->getAmount());
    }

    public function testGetAmountThrowsOnInvalidKennzeichen(): void
    {
        $sdo = new Sdo();
        $sdo->sollHabenKennzeichen = 'X';
        $sdo->betrag = Btg::create(100.00, 'EUR');
        $sdo->datum = '20240115';

        $this->expectException(\InvalidArgumentException::class);
        $sdo->getAmount();
    }

    public function testGetCurrency(): void
    {
        $sdo = new Sdo();
        $sdo->sollHabenKennzeichen = Sdo::CREDIT;
        $sdo->betrag = Btg::create(100.00, 'EUR');
        $sdo->datum = '20240115';

        $this->assertSame('EUR', $sdo->getCurrency());
    }

    public function testGetTimestampWithTime(): void
    {
        $sdo = new Sdo();
        $sdo->sollHabenKennzeichen = Sdo::CREDIT;
        $sdo->betrag = Btg::create(100.00, 'EUR');
        $sdo->datum = '20240115';
        $sdo->uhrzeit = '143000';

        $timestamp = $sdo->getTimestamp();
        $this->assertSame('2024-01-15', $timestamp->format('Y-m-d'));
        $this->assertSame('14:30:00', $timestamp->format('H:i:s'));
    }

    public function testGetTimestampWithoutTime(): void
    {
        $sdo = new Sdo();
        $sdo->sollHabenKennzeichen = Sdo::CREDIT;
        $sdo->betrag = Btg::create(100.00, 'EUR');
        $sdo->datum = '20240115';
        $sdo->uhrzeit = null;

        $timestamp = $sdo->getTimestamp();
        $this->assertSame('2024-01-15', $timestamp->format('Y-m-d'));
        $this->assertSame('00:00:00', $timestamp->format('H:i:s'));
    }
}
