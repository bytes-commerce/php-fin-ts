<?php

namespace BytesCommerce\Tests\Unit\Segment\WPD;

use BytesCommerce\Segment\WPD\ParameterDepotaufstellungV2;
use PHPUnit\Framework\TestCase;

final class ParameterDepotaufstellungV2Test extends TestCase
{
    public function testGetEingabeAnzahlEintraegeErlaubtTrue(): void
    {
        $parameterDepotaufstellungV2 = new ParameterDepotaufstellungV2();
        $parameterDepotaufstellungV2->eingabeAnzahlEintraegeErlaubt = true;

        $this->assertTrue($parameterDepotaufstellungV2->getEingabeAnzahlEintraegeErlaubt());
    }

    public function testGetEingabeAnzahlEintraegeErlaubtFalse(): void
    {
        $parameterDepotaufstellungV2 = new ParameterDepotaufstellungV2();
        $parameterDepotaufstellungV2->eingabeAnzahlEintraegeErlaubt = false;

        $this->assertFalse($parameterDepotaufstellungV2->getEingabeAnzahlEintraegeErlaubt());
    }

    public function testGetWaehrungDepotaufstellungWaehlbarTrue(): void
    {
        $parameterDepotaufstellungV2 = new ParameterDepotaufstellungV2();
        $parameterDepotaufstellungV2->waehrungDepotaufstellungWaehlbar = true;

        $this->assertTrue($parameterDepotaufstellungV2->getWaehrungDepotaufstellungWaehlbar());
    }

    public function testGetWaehrungDepotaufstellungWaehlbarFalse(): void
    {
        $parameterDepotaufstellungV2 = new ParameterDepotaufstellungV2();
        $parameterDepotaufstellungV2->waehrungDepotaufstellungWaehlbar = false;

        $this->assertFalse($parameterDepotaufstellungV2->getWaehrungDepotaufstellungWaehlbar());
    }

    public function testGetKursqualitaetWaehlbarTrue(): void
    {
        $parameterDepotaufstellungV2 = new ParameterDepotaufstellungV2();
        $parameterDepotaufstellungV2->kursqualitaetWaehlbar = true;

        $this->assertTrue($parameterDepotaufstellungV2->getKursqualitaetWaehlbar());
    }

    public function testGetKursqualitaetWaehlbarFalse(): void
    {
        $parameterDepotaufstellungV2 = new ParameterDepotaufstellungV2();
        $parameterDepotaufstellungV2->kursqualitaetWaehlbar = false;

        $this->assertFalse($parameterDepotaufstellungV2->getKursqualitaetWaehlbar());
    }
}
