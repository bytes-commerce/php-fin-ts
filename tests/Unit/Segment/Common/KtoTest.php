<?php

namespace BytesCommerce\Tests\Unit\Segment\Common;

use BytesCommerce\Model\SEPAAccount;
use BytesCommerce\Segment\Common\Kik;
use BytesCommerce\Segment\Common\Kto;
use PHPUnit\Framework\TestCase;

final class KtoTest extends TestCase
{
    public function testCreateWithKontonummerAndKik(): void
    {
        $kik = Kik::create('70000000');
        $kto = Kto::create('1234567890', $kik);

        $this->assertSame('1234567890', $kto->kontonummer);
        $this->assertSame($kik, $kto->kik);
    }

    public function testFromAccountWithSepaAccount(): void
    {
        $sepaAccount = new SEPAAccount();
        $sepaAccount->setAccountNumber('1234567890');
        $sepaAccount->setBlz('70000000');

        $kto = Kto::fromAccount($sepaAccount);

        $this->assertSame('1234567890', $kto->kontonummer);
        $this->assertSame('70000000', $kto->kik->kreditinstitutscode);
        $this->assertSame('280', $kto->kik->laenderkennzeichen);
    }

    public function testGetAccountNumber(): void
    {
        $kik = Kik::create('70000000');
        $kto = Kto::create('1234567890', $kik);

        $this->assertSame('1234567890', $kto->getAccountNumber());
    }

    public function testGetBankIdentifier(): void
    {
        $kik = Kik::create('70000000');
        $kto = Kto::create('1234567890', $kik);

        $this->assertSame('70000000', $kto->getBankIdentifier());
    }

    public function testFromAccountWithAccountHavingBlz(): void
    {
        $sepaAccount = new SEPAAccount();
        $sepaAccount->setAccountNumber('DE1234567890');
        $sepaAccount->setBlz('50010000');

        $kto = Kto::fromAccount($sepaAccount);

        $this->assertSame('DE1234567890', $kto->getAccountNumber());
        $this->assertSame('50010000', $kto->getBankIdentifier());
    }

    public function testGetKontonummer(): void
    {
        $kik = Kik::create('70000000');
        $kto = Kto::create('9876543210', $kik);

        $this->assertSame('9876543210', $kto->getKontonummer());
    }

    public function testGetKik(): void
    {
        $kik = Kik::create('70000000');
        $kto = Kto::create('1234567890', $kik);

        $this->assertSame($kik, $kto->getKik());
    }
}
