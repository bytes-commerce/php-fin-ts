<?php

namespace BytesCommerce\Tests\Unit\Segment\WPD;

use BytesCommerce\Segment\Common\Kik;
use BytesCommerce\Segment\Common\KtvV3;
use BytesCommerce\Segment\Common\Kursqualitaet;
use BytesCommerce\Segment\WPD\HKWPDv5;
use PHPUnit\Framework\TestCase;

final class HKWPDv5Test extends TestCase
{
    public function testCreate(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);

        $hkwpDv5 = HKWPDv5::create($ktvV3);

        $this->assertSame($ktvV3, $hkwpDv5->getDepot());
    }

    public function testCreateWithAllParameters(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);
        $kursqualitaet = new Kursqualitaet();
        $kursqualitaet->kursqualitaet = Kursqualitaet::REALTIME;

        $hkwpDv5 = HKWPDv5::create($ktvV3);
        $hkwpDv5->waehrungDerDepotaufstellung = 'EUR';
        $hkwpDv5->kursqualitaet = $kursqualitaet;
        $hkwpDv5->maximaleAnzahlEintraege = 100;

        $this->assertSame('EUR', $hkwpDv5->waehrungDerDepotaufstellung);
        $this->assertSame(Kursqualitaet::REALTIME, $hkwpDv5->kursqualitaet->getKursqualitaet());
        $this->assertSame(100, $hkwpDv5->maximaleAnzahlEintraege);
    }

    public function testSetPaginationToken(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);
        $hkwpDv5 = HKWPDv5::create($ktvV3);

        $hkwpDv5->setPaginationToken('nextPageToken');

        $this->assertSame('nextPageToken', $hkwpDv5->getAufsetzpunkt());
    }

    public function testGetWaehrungDerDepotaufstellungNull(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);
        $hkwpDv5 = HKWPDv5::create($ktvV3);

        $this->assertNull($hkwpDv5->waehrungDerDepotaufstellung);
    }

    public function testGetKursqualitaetNull(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);
        $hkwpDv5 = HKWPDv5::create($ktvV3);

        $this->assertNull($hkwpDv5->kursqualitaet);
    }

    public function testGetMaximaleAnzahlEintraegeNull(): void
    {
        $kik = Kik::create('70000000');
        $ktvV3 = KtvV3::create('1234567890', null, $kik);
        $hkwpDv5 = HKWPDv5::create($ktvV3);

        $this->assertNull($hkwpDv5->maximaleAnzahlEintraege);
    }
}
