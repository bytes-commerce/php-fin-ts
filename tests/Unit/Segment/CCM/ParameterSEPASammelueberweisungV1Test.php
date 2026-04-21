<?php

namespace BytesCommerce\Tests\Unit\Segment\CCM;

use BytesCommerce\Segment\CCM\ParameterSEPASammelueberweisungV1;
use PHPUnit\Framework\TestCase;

final class ParameterSEPASammelueberweisungV1Test extends TestCase
{
    public function testGetMaximaleAnzahlCreditTransferTransactionInformation(): void
    {
        $parameterSEPASammelueberweisungV1 = new ParameterSEPASammelueberweisungV1();
        $parameterSEPASammelueberweisungV1->maximaleAnzahlCreditTransferTransactionInformation = 50;

        $this->assertSame(50, $parameterSEPASammelueberweisungV1->getMaximaleAnzahlCreditTransferTransactionInformation());
    }

    public function testGetSummenfeldBenoetigtTrue(): void
    {
        $parameterSEPASammelueberweisungV1 = new ParameterSEPASammelueberweisungV1();
        $parameterSEPASammelueberweisungV1->summenfeldBenoetigt = true;

        $this->assertTrue($parameterSEPASammelueberweisungV1->getSummenfeldBenoetigt());
    }

    public function testGetSummenfeldBenoetigtFalse(): void
    {
        $parameterSEPASammelueberweisungV1 = new ParameterSEPASammelueberweisungV1();
        $parameterSEPASammelueberweisungV1->summenfeldBenoetigt = false;

        $this->assertFalse($parameterSEPASammelueberweisungV1->getSummenfeldBenoetigt());
    }

    public function testGetEinzelbuchungErlaubtTrue(): void
    {
        $parameterSEPASammelueberweisungV1 = new ParameterSEPASammelueberweisungV1();
        $parameterSEPASammelueberweisungV1->einzelbuchungErlaubt = true;

        $this->assertTrue($parameterSEPASammelueberweisungV1->getEinzelbuchungErlaubt());
    }

    public function testGetEinzelbuchungErlaubtFalse(): void
    {
        $parameterSEPASammelueberweisungV1 = new ParameterSEPASammelueberweisungV1();
        $parameterSEPASammelueberweisungV1->einzelbuchungErlaubt = false;

        $this->assertFalse($parameterSEPASammelueberweisungV1->getEinzelbuchungErlaubt());
    }
}
