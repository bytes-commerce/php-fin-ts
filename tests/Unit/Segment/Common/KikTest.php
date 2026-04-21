<?php

namespace BytesCommerce\Tests\Unit\Segment\Common;

use BytesCommerce\Segment\Common\Kik;
use PHPUnit\Framework\TestCase;

final class KikTest extends TestCase
{
    public function testCreateWithBlz(): void
    {
        $kik = Kik::create('70000000');

        $this->assertSame('280', $kik->laenderkennzeichen);
        $this->assertSame('70000000', $kik->kreditinstitutscode);
    }

    public function testCreateSetsDefaultCountryCode(): void
    {
        $kik = Kik::create('70000000');

        $this->assertSame(Kik::DEFAULT_COUNTRY_CODE, $kik->laenderkennzeichen);
    }

    public function testGetLaenderkennzeichen(): void
    {
        $kik = Kik::create('70000000');

        $this->assertSame('280', $kik->getLaenderkennzeichen());
    }

    public function testGetKreditinstitutscode(): void
    {
        $kik = Kik::create('70000000');

        $this->assertSame('70000000', $kik->getKreditinstitutscode());
    }

    public function testValidateWithGermanBankAndNoBlzThrowsException(): void
    {
        $kik = new Kik();
        $kik->laenderkennzeichen = Kik::DEFAULT_COUNTRY_CODE;
        $kik->kreditinstitutscode = null;

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Kik.kreditinstitutscode is mandatory for German banks');

        $kik->validate();
    }

    public function testValidateWithGermanBankAndBlzPasses(): void
    {
        $kik = Kik::create('70000000');

        $kik->validate();

        $this->assertTrue(true);
    }

    public function testValidateWithNonGermanBankPasses(): void
    {
        $kik = new Kik();
        $kik->laenderkennzeichen = '840';
        $kik->kreditinstitutscode = null;

        $kik->validate();

        $this->assertTrue(true);
    }

    public function testLaenderkennzeichenCanBeNull(): void
    {
        $kik = new Kik();
        $kik->laenderkennzeichen = null;
        $kik->kreditinstitutscode = '70000000';

        $kik->validate();

        $this->assertNull($kik->laenderkennzeichen);
    }

    public function testKreditinstitutscodeCanBeSetDirectly(): void
    {
        $kik = new Kik();
        $kik->laenderkennzeichen = '280';
        $kik->kreditinstitutscode = '70000000';

        $this->assertSame('70000000', $kik->kreditinstitutscode);
    }
}
