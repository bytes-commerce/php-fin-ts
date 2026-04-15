<?php

namespace Fhp\Tests\Unit\Segment\WPD;

use Fhp\Segment\Common\Kik;
use Fhp\Segment\Common\KtvV3;
use Fhp\Segment\Common\Kursqualitaet;
use Fhp\Segment\WPD\HKWPDv5;
use PHPUnit\Framework\TestCase;

final class HKWPDv5Test extends TestCase
{
    public function testCreate(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);

        $hkwpd = HKWPDv5::create($ktv);

        $this->assertSame($ktv, $hkwpd->getDepot());
    }

    public function testCreateWithAllParameters(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);
        $kursqualitaet = new Kursqualitaet();
        $kursqualitaet->kursqualitaet = Kursqualitaet::REALTIME;

        $hkwpd = HKWPDv5::create($ktv);
        $hkwpd->waehrungDerDepotaufstellung = 'EUR';
        $hkwpd->kursqualitaet = $kursqualitaet;
        $hkwpd->maximaleAnzahlEintraege = 100;

        $this->assertSame('EUR', $hkwpd->waehrungDerDepotaufstellung);
        $this->assertSame(Kursqualitaet::REALTIME, $hkwpd->kursqualitaet->getKursqualitaet());
        $this->assertSame(100, $hkwpd->maximaleAnzahlEintraege);
    }

    public function testSetPaginationToken(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);
        $hkwpd = HKWPDv5::create($ktv);

        $hkwpd->setPaginationToken('nextPageToken');

        $this->assertSame('nextPageToken', $hkwpd->getAufsetzpunkt());
    }

    public function testGetWaehrungDerDepotaufstellungNull(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);
        $hkwpd = HKWPDv5::create($ktv);

        $this->assertNull($hkwpd->waehrungDerDepotaufstellung);
    }

    public function testGetKursqualitaetNull(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);
        $hkwpd = HKWPDv5::create($ktv);

        $this->assertNull($hkwpd->kursqualitaet);
    }

    public function testGetMaximaleAnzahlEintraegeNull(): void
    {
        $kik = Kik::create('70000000');
        $ktv = KtvV3::create('1234567890', null, $kik);
        $hkwpd = HKWPDv5::create($ktv);

        $this->assertNull($hkwpd->maximaleAnzahlEintraege);
    }
}
