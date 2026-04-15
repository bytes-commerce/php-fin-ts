<?php

namespace Fhp\Tests\Unit\Segment\WPD;

use Fhp\Segment\WPD\ParameterDepotaufstellungV2;
use PHPUnit\Framework\TestCase;

final class ParameterDepotaufstellungV2Test extends TestCase
{
    public function testGetEingabeAnzahlEintraegeErlaubtTrue(): void
    {
        $param = new ParameterDepotaufstellungV2();
        $param->eingabeAnzahlEintraegeErlaubt = true;

        $this->assertTrue($param->getEingabeAnzahlEintraegeErlaubt());
    }

    public function testGetEingabeAnzahlEintraegeErlaubtFalse(): void
    {
        $param = new ParameterDepotaufstellungV2();
        $param->eingabeAnzahlEintraegeErlaubt = false;

        $this->assertFalse($param->getEingabeAnzahlEintraegeErlaubt());
    }

    public function testGetWaehrungDepotaufstellungWaehlbarTrue(): void
    {
        $param = new ParameterDepotaufstellungV2();
        $param->waehrungDepotaufstellungWaehlbar = true;

        $this->assertTrue($param->getWaehrungDepotaufstellungWaehlbar());
    }

    public function testGetWaehrungDepotaufstellungWaehlbarFalse(): void
    {
        $param = new ParameterDepotaufstellungV2();
        $param->waehrungDepotaufstellungWaehlbar = false;

        $this->assertFalse($param->getWaehrungDepotaufstellungWaehlbar());
    }

    public function testGetKursqualitaetWaehlbarTrue(): void
    {
        $param = new ParameterDepotaufstellungV2();
        $param->kursqualitaetWaehlbar = true;

        $this->assertTrue($param->getKursqualitaetWaehlbar());
    }

    public function testGetKursqualitaetWaehlbarFalse(): void
    {
        $param = new ParameterDepotaufstellungV2();
        $param->kursqualitaetWaehlbar = false;

        $this->assertFalse($param->getKursqualitaetWaehlbar());
    }
}
