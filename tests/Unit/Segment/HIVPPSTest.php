<?php

namespace BytesCommerce\Tests\Unit\Segment;

use BytesCommerce\Segment\VPP\HIVPPSv1;
use BytesCommerce\Segment\VPP\ParameterNamensabgleichPruefauftragV1;
use BytesCommerce\Syntax\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Among other things, this test covers the serialization of arrays with @Max annotation.
 */
class HIVPPSTest extends TestCase
{
    private HIVPPSv1 $hivppSv1;

    protected function setUp(): void
    {
        $this->hivppSv1 = HIVPPSv1::createEmpty();
        $this->hivppSv1->setSegmentNumber(42);
        $this->hivppSv1->maximaleAnzahlAuftraege = 43;
        $this->hivppSv1->anzahlSignaturenMindestens = 44;
        $this->hivppSv1->sicherheitsklasse = 45;
        $this->hivppSv1->parameter = new ParameterNamensabgleichPruefauftragV1();
        $this->hivppSv1->parameter->maximaleAnzahlCreditTransferTransactionInformationOptIn = 1;
        $this->hivppSv1->parameter->aufklaerungstextStrukturiert = true;
        $this->hivppSv1->parameter->artDerLieferungPaymentStatusReport = 'Art';
        $this->hivppSv1->parameter->sammelzahlungenMitEinemAuftragErlaubt = false;
        $this->hivppSv1->parameter->eingabeAnzahlEintraegeErlaubt = false;
        $this->hivppSv1->parameter->unterstuetztePaymentStatusReportDatenformate = 'Test';
    }

    public function testPopulatedArray(): void
    {
        $this->hivppSv1->parameter->vopPflichtigerZahlungsverkehrsauftrag = ['HKFOO', 'HKBAR'];

        $serialized = $this->hivppSv1->serialize();
        $this->assertEquals("HIVPPS:42:1+43+44+45+1:J:Art:N:N:Test:HKFOO:HKBAR'", $serialized);

        /** @var HIVPPSv1 $baseSegment */
        $baseSegment = Parser::parseSegment($serialized, HIVPPSv1::class);
        $this->assertEquals(['HKFOO', 'HKBAR'], $baseSegment->parameter->vopPflichtigerZahlungsverkehrsauftrag);
    }
}
