<?php

namespace Fhp\Tests\Unit\Segment\CME;

use Fhp\Segment\CME\ParameterTerminierteSEPASammelueberweisungEinreichenV1;
use PHPUnit\Framework\TestCase;

final class ParameterTerminierteSEPASammelueberweisungEinreichenV1Test extends TestCase
{
    public function testGetMinimaleVorlaufzeit(): void
    {
        $param = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $param->minimaleVorlaufzeit = 5;

        $this->assertSame(5, $param->getMinimaleVorlaufzeit());
    }

    public function testGetMaximaleVorlaufzeit(): void
    {
        $param = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $param->maximaleVorlaufzeit = 90;

        $this->assertSame(90, $param->getMaximaleVorlaufzeit());
    }

    public function testGetMaximaleAnzahlCreditTransferTransactionInformation(): void
    {
        $param = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $param->maximaleAnzahlCreditTransferTransactionInformation = 100;

        $this->assertSame(100, $param->getMaximaleAnzahlCreditTransferTransactionInformation());
    }

    public function testGetSummenfeldBenoetigtTrue(): void
    {
        $param = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $param->summenfeldBenoetigt = true;

        $this->assertTrue($param->getSummenfeldBenoetigt());
    }

    public function testGetSummenfeldBenoetigtFalse(): void
    {
        $param = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $param->summenfeldBenoetigt = false;

        $this->assertFalse($param->getSummenfeldBenoetigt());
    }

    public function testGetEinzelbuchungErlaubtTrue(): void
    {
        $param = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $param->einzelbuchungErlaubt = true;

        $this->assertTrue($param->getEinzelbuchungErlaubt());
    }

    public function testGetEinzelbuchungErlaubtFalse(): void
    {
        $param = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $param->einzelbuchungErlaubt = false;

        $this->assertFalse($param->getEinzelbuchungErlaubt());
    }
}
