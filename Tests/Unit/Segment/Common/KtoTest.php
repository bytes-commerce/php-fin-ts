<?php

namespace Fhp\Tests\Unit\Segment\Common;

use Fhp\Model\SEPAAccount;
use Fhp\Segment\Common\Kik;
use Fhp\Segment\Common\Kto;
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
        $account = new SEPAAccount();
        $account->setAccountNumber('1234567890');
        $account->setBlz('70000000');

        $kto = Kto::fromAccount($account);

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
        $account = new SEPAAccount();
        $account->setAccountNumber('DE1234567890');
        $account->setBlz('50010000');

        $kto = Kto::fromAccount($account);

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
