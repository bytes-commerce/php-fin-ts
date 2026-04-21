<?php

namespace BytesCommerce\Tests\Unit\Segment\Common;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\Segment\Common\Kik;
use BytesCommerce\Segment\Common\KtvV3;
use PHPUnit\Framework\TestCase;

final class KtvV3Test extends TestCase
{
    public function testCreate(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', '001', $kik);

        $this->assertSame('1234567890', $ktvV3->kontonummer);
        $this->assertSame('001', $ktvV3->unterkontomerkmal);
        $this->assertSame($kik, $ktvV3->kik);
    }

    public function testGetKontonummer(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);

        $this->assertSame('1234567890', $ktvV3->getKontonummer());
    }

    public function testGetUnterkontomerkmal(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', '002', $kik);

        $this->assertSame('002', $ktvV3->getUnterkontomerkmal());
    }

    public function testGetUnterkontomerkmalReturnsNull(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);

        $this->assertNull($ktvV3->getUnterkontomerkmal());
    }

    public function testGetKik(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);

        $this->assertSame($kik, $ktvV3->getKik());
    }

    public function testGetAccountNumber(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);

        $this->assertSame('1234567890', $ktvV3->getAccountNumber());
    }

    public function testGetBankIdentifier(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);

        $this->assertSame('70000000', $ktvV3->getBankIdentifier());
    }

    public function testFromAccount(): void
    {
        $sepaAccount = new SEPAAccount();
        $sepaAccount->setAccountNumber('1234567890');
        $sepaAccount->setBlz('70000000');
        $sepaAccount->setSubAccount('005');

        $ktvV3 = KtvV3::fromAccount($sepaAccount);

        $this->assertSame('1234567890', $ktvV3->getKontonummer());
        $this->assertSame('005', $ktvV3->getUnterkontomerkmal());
        $this->assertSame('70000000', $ktvV3->getKik()->kreditinstitutscode);
    }
}
