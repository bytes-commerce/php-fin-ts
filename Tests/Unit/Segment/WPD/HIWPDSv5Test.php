<?php

namespace Fhp\Tests\Unit\Segment\WPD;

use Fhp\Segment\WPD\HIWPDSv5;
use Fhp\Segment\WPD\ParameterDepotaufstellungV2;
use PHPUnit\Framework\TestCase;

final class HIWPDSv5Test extends TestCase
{
    public function testGetParameter(): void
    {
        $hiwpds = new HIWPDSv5();
        $param = new ParameterDepotaufstellungV2();
        $param->eingabeAnzahlEintraegeErlaubt = true;
        $param->waehrungDepotaufstellungWaehlbar = false;
        $param->kursqualitaetWaehlbar = true;
        $hiwpds->parameter = $param;

        $this->assertSame($param, $hiwpds->getParameter());
        $this->assertTrue($hiwpds->getParameter()->getEingabeAnzahlEintraegeErlaubt());
        $this->assertFalse($hiwpds->getParameter()->getWaehrungDepotaufstellungWaehlbar());
        $this->assertTrue($hiwpds->getParameter()->getKursqualitaetWaehlbar());
    }
}
