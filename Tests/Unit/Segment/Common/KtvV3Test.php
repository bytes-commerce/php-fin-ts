<?php

namespace Fhp\Tests\Unit\Segment\Common;

use Fhp\Model\SEPAAccount;
use Fhp\Segment\Common\Kik;
use Fhp\Segment\Common\KtvV3;
use PHPUnit\Framework\TestCase;

final class KtvV3Test extends TestCase
{
    public function testCreate(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', '001', $kik);

        $this->assertSame('1234567890', $ktv->kontonummer);
        $this->assertSame('001', $ktv->unterkontomerkmal);
        $this->assertSame($kik, $ktv->kik);
    }

    public function testGetKontonummer(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);

        $this->assertSame('1234567890', $ktv->getKontonummer());
    }

    public function testGetUnterkontomerkmal(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', '002', $kik);

        $this->assertSame('002', $ktv->getUnterkontomerkmal());
    }

    public function testGetUnterkontomerkmalReturnsNull(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);

        $this->assertNull($ktv->getUnterkontomerkmal());
    }

    public function testGetKik(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);

        $this->assertSame($kik, $ktv->getKik());
    }

    public function testGetAccountNumber(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);

        $this->assertSame('1234567890', $ktv->getAccountNumber());
    }

    public function testGetBankIdentifier(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);

        $this->assertSame('70000000', $ktv->getBankIdentifier());
    }

    public function testFromAccount(): void
    {
        $account = new SEPAAccount();
        $account->setAccountNumber('1234567890');
        $account->setBlz('70000000');
        $account->setSubAccount('005');

        $ktv = KtvV3::fromAccount($account);

        $this->assertSame('1234567890', $ktv->getKontonummer());
        $this->assertSame('005', $ktv->getUnterkontomerkmal());
        $this->assertSame('70000000', $ktv->getKik()->kreditinstitutscode);
    }
}
