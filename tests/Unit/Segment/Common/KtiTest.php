<?php

namespace BytesCommerce\Tests\Unit\Segment\Common;

use BytesCommerce\Segment\Common\Kik;
use BytesCommerce\Segment\Common\Kti;
use PHPUnit\Framework\TestCase;

final class KtiTest extends TestCase
{
    public function testCreate(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');

        $this->assertSame('DE89370400440532013000', $kti->iban);
        $this->assertSame('COBADEFFXXX', $kti->bic);
    }

    public function testGetIban(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');

        $this->assertSame('DE89370400440532013000', $kti->getIban());
    }

    public function testGetBic(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');

        $this->assertSame('COBADEFFXXX', $kti->getBic());
    }

    public function testGetKontonummer(): void
    {
        $kti = new Kti();
        $kti->kontonummer = '1234567890';

        $this->assertSame('1234567890', $kti->getKontonummer());
    }

    public function testGetUnterkontomerkmal(): void
    {
        $kti = new Kti();
        $kti->unterkontomerkmal = '001';

        $this->assertSame('001', $kti->getUnterkontomerkmal());
    }

    public function testGetKreditinstitutskennung(): void
    {
        $kik = Kik::create('70000000');
        $kti = new Kti();
        $kti->kreditinstitutskennung = $kik;

        $this->assertSame($kik, $kti->getKreditinstitutskennung());
    }

    public function testGetAccountNumberReturnsIbanWhenPresent(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');

        $this->assertSame('DE89370400440532013000', $kti->getAccountNumber());
    }

    public function testGetAccountNumberReturnsKontonummerWhenNoIban(): void
    {
        $kti = new Kti();
        $kti->kontonummer = '1234567890';

        $this->assertSame('1234567890', $kti->getAccountNumber());
    }

    public function testGetBankIdentifierReturnsBicWhenPresent(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');

        $this->assertSame('COBADEFFXXX', $kti->getBankIdentifier());
    }

    public function testGetBankIdentifierReturnsKikCodeWhenNoBic(): void
    {
        $kik = Kik::create('70000000');
        $kti = new Kti();
        $kti->kontonummer = '1234567890';
        $kti->kreditinstitutskennung = $kik;

        $this->assertSame('70000000', $kti->getBankIdentifier());
    }
}
