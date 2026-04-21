<?php

namespace BytesCommerce\Tests\Unit\Segment\WPD;

use BytesCommerce\Segment\WPD\HIWPDSv5;
use BytesCommerce\Segment\WPD\ParameterDepotaufstellungV2;
use PHPUnit\Framework\TestCase;

final class HIWPDSv5Test extends TestCase
{
    public function testGetParameter(): void
    {
        $hiwpdSv5 = new HIWPDSv5();
        $parameterDepotaufstellungV2 = new ParameterDepotaufstellungV2();
        $parameterDepotaufstellungV2->eingabeAnzahlEintraegeErlaubt = true;
        $parameterDepotaufstellungV2->waehrungDepotaufstellungWaehlbar = false;
        $parameterDepotaufstellungV2->kursqualitaetWaehlbar = true;

        $hiwpdSv5->parameter = $parameterDepotaufstellungV2;

        $this->assertSame($parameterDepotaufstellungV2, $hiwpdSv5->getParameter());
        $this->assertTrue($hiwpdSv5->getParameter()->getEingabeAnzahlEintraegeErlaubt());
        $this->assertFalse($hiwpdSv5->getParameter()->getWaehrungDepotaufstellungWaehlbar());
        $this->assertTrue($hiwpdSv5->getParameter()->getKursqualitaetWaehlbar());
    }
}
