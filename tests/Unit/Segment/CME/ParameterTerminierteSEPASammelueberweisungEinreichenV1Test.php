<?php

namespace BytesCommerce\Tests\Unit\Segment\CME;

use BytesCommerce\Segment\CME\ParameterTerminierteSEPASammelueberweisungEinreichenV1;
use PHPUnit\Framework\TestCase;

final class ParameterTerminierteSEPASammelueberweisungEinreichenV1Test extends TestCase
{
    public function testGetMinimaleVorlaufzeit(): void
    {
        $parameterTerminierteSEPASammelueberweisungEinreichenV1 = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $parameterTerminierteSEPASammelueberweisungEinreichenV1->minimaleVorlaufzeit = 5;

        $this->assertSame(5, $parameterTerminierteSEPASammelueberweisungEinreichenV1->getMinimaleVorlaufzeit());
    }

    public function testGetMaximaleVorlaufzeit(): void
    {
        $parameterTerminierteSEPASammelueberweisungEinreichenV1 = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $parameterTerminierteSEPASammelueberweisungEinreichenV1->maximaleVorlaufzeit = 90;

        $this->assertSame(90, $parameterTerminierteSEPASammelueberweisungEinreichenV1->getMaximaleVorlaufzeit());
    }

    public function testGetMaximaleAnzahlCreditTransferTransactionInformation(): void
    {
        $parameterTerminierteSEPASammelueberweisungEinreichenV1 = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $parameterTerminierteSEPASammelueberweisungEinreichenV1->maximaleAnzahlCreditTransferTransactionInformation = 100;

        $this->assertSame(100, $parameterTerminierteSEPASammelueberweisungEinreichenV1->getMaximaleAnzahlCreditTransferTransactionInformation());
    }

    public function testGetSummenfeldBenoetigtTrue(): void
    {
        $parameterTerminierteSEPASammelueberweisungEinreichenV1 = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $parameterTerminierteSEPASammelueberweisungEinreichenV1->summenfeldBenoetigt = true;

        $this->assertTrue($parameterTerminierteSEPASammelueberweisungEinreichenV1->getSummenfeldBenoetigt());
    }

    public function testGetSummenfeldBenoetigtFalse(): void
    {
        $parameterTerminierteSEPASammelueberweisungEinreichenV1 = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $parameterTerminierteSEPASammelueberweisungEinreichenV1->summenfeldBenoetigt = false;

        $this->assertFalse($parameterTerminierteSEPASammelueberweisungEinreichenV1->getSummenfeldBenoetigt());
    }

    public function testGetEinzelbuchungErlaubtTrue(): void
    {
        $parameterTerminierteSEPASammelueberweisungEinreichenV1 = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $parameterTerminierteSEPASammelueberweisungEinreichenV1->einzelbuchungErlaubt = true;

        $this->assertTrue($parameterTerminierteSEPASammelueberweisungEinreichenV1->getEinzelbuchungErlaubt());
    }

    public function testGetEinzelbuchungErlaubtFalse(): void
    {
        $parameterTerminierteSEPASammelueberweisungEinreichenV1 = new ParameterTerminierteSEPASammelueberweisungEinreichenV1();
        $parameterTerminierteSEPASammelueberweisungEinreichenV1->einzelbuchungErlaubt = false;

        $this->assertFalse($parameterTerminierteSEPASammelueberweisungEinreichenV1->getEinzelbuchungErlaubt());
    }
}
