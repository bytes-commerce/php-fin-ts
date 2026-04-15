<?php

namespace Fhp\Tests\Unit\Segment\CCM;

use Fhp\Segment\CCM\ParameterSEPASammelueberweisungV1;
use PHPUnit\Framework\TestCase;

final class ParameterSEPASammelueberweisungV1Test extends TestCase
{
    public function testGetMaximaleAnzahlCreditTransferTransactionInformation(): void
    {
        $param = new ParameterSEPASammelueberweisungV1();
        $param->maximaleAnzahlCreditTransferTransactionInformation = 50;

        $this->assertSame(50, $param->getMaximaleAnzahlCreditTransferTransactionInformation());
    }

    public function testGetSummenfeldBenoetigtTrue(): void
    {
        $param = new ParameterSEPASammelueberweisungV1();
        $param->summenfeldBenoetigt = true;

        $this->assertTrue($param->getSummenfeldBenoetigt());
    }

    public function testGetSummenfeldBenoetigtFalse(): void
    {
        $param = new ParameterSEPASammelueberweisungV1();
        $param->summenfeldBenoetigt = false;

        $this->assertFalse($param->getSummenfeldBenoetigt());
    }

    public function testGetEinzelbuchungErlaubtTrue(): void
    {
        $param = new ParameterSEPASammelueberweisungV1();
        $param->einzelbuchungErlaubt = true;

        $this->assertTrue($param->getEinzelbuchungErlaubt());
    }

    public function testGetEinzelbuchungErlaubtFalse(): void
    {
        $param = new ParameterSEPASammelueberweisungV1();
        $param->einzelbuchungErlaubt = false;

        $this->assertFalse($param->getEinzelbuchungErlaubt());
    }
}
