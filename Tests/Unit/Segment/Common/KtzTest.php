<?php

namespace Fhp\Tests\Unit\Segment\Common;

use Fhp\Segment\Common\Kik;
use Fhp\Segment\Common\Ktz;
use PHPUnit\Framework\TestCase;

final class KtzTest extends TestCase
{
    public function testGetAccountNumberWithIban(): void
    {
        $ktz = new Ktz();
        $ktz->iban = 'DE89370400440532013000';
        $ktz->kontonummer = '1234567890';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('DE89370400440532013000', $ktz->getAccountNumber());
    }

    public function testGetAccountNumberWithoutIban(): void
    {
        $ktz = new Ktz();
        $ktz->iban = null;
        $ktz->kontonummer = '1234567890';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('1234567890', $ktz->getAccountNumber());
    }

    public function testGetBankIdentifierWithBic(): void
    {
        $ktz = new Ktz();
        $ktz->bic = 'COBADEFFXXX';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('COBADEFFXXX', $ktz->getBankIdentifier());
    }

    public function testGetBankIdentifierWithoutBic(): void
    {
        $ktz = new Ktz();
        $ktz->bic = null;
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('70000000', $ktz->getBankIdentifier());
    }

    public function testGetKontoverwendungSepa(): void
    {
        $ktz = new Ktz();
        $ktz->kontoverwendungSepa = true;
        $ktz->kontonummer = '123';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertTrue($ktz->getKontoverwendungSepa());
    }

    public function testGetIban(): void
    {
        $ktz = new Ktz();
        $ktz->iban = 'DE89370400440532013000';
        $ktz->kontonummer = '1234567890';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('DE89370400440532013000', $ktz->getIban());
    }

    public function testGetBic(): void
    {
        $ktz = new Ktz();
        $ktz->bic = 'COBADEFFXXX';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('COBADEFFXXX', $ktz->getBic());
    }

    public function testGetKontonummer(): void
    {
        $ktz = new Ktz();
        $ktz->kontonummer = '1234567890';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('1234567890', $ktz->getKontonummer());
    }

    public function testGetUnterkontomerkmal(): void
    {
        $ktz = new Ktz();
        $ktz->kontonummer = '123';
        $ktz->unterkontomerkmal = '001';
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertSame('001', $ktz->getUnterkontomerkmal());
    }

    public function testGetUnterkontomerkmalReturnsNull(): void
    {
        $ktz = new Ktz();
        $ktz->kontonummer = '123';
        $ktz->unterkontomerkmal = null;
        $ktz->kreditinstitutskennung = Kik::create('70000000');

        $this->assertNull($ktz->getUnterkontomerkmal());
    }

    public function testGetKreditinstitutskennung(): void
    {
        $kik = Kik::create('70000000');
        $ktz = new Ktz();
        $ktz->kontonummer = '123';
        $ktz->kreditinstitutskennung = $kik;

        $this->assertSame($kik, $ktz->getKreditinstitutskennung());
    }
}
