<?php

namespace Fhp\Tests\Unit\Segment\KAZ;

use Fhp\Segment\Common\Kti;
use Fhp\Segment\KAZ\HKKAZv7;
use PHPUnit\Framework\TestCase;

final class HKKAZv7Test extends TestCase
{
    public function testCreate(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $vonDatum = new \DateTime('2024-01-01');
        $bisDatum = new \DateTime('2024-01-31');

        $hkkaz = HKKAZv7::create($kti, false, $vonDatum, $bisDatum);

        $this->assertSame($kti, $hkkaz->getKontoverbindungInternational());
        $this->assertFalse($hkkaz->getAlleKonten());
        $this->assertSame('20240101', $hkkaz->getVonDatum());
        $this->assertSame('20240131', $hkkaz->getBisDatum());
    }

    public function testCreateWithAllParameters(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $vonDatum = new \DateTime('2024-01-01');
        $bisDatum = new \DateTime('2024-01-31');
        $aufsetzpunkt = 'token123';

        $hkkaz = HKKAZv7::create($kti, true, $vonDatum, $bisDatum, $aufsetzpunkt);

        $this->assertSame($kti, $hkkaz->getKontoverbindungInternational());
        $this->assertTrue($hkkaz->getAlleKonten());
        $this->assertSame('20240101', $hkkaz->getVonDatum());
        $this->assertSame('20240131', $hkkaz->getBisDatum());
        $this->assertSame('token123', $hkkaz->getAufsetzpunkt());
    }

    public function testCreateWithNullDates(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');

        $hkkaz = HKKAZv7::create($kti, false, null, null);

        $this->assertNull($hkkaz->getVonDatum());
        $this->assertNull($hkkaz->getBisDatum());
    }

    public function testSetPaginationToken(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $hkkaz = HKKAZv7::create($kti, false, null, null);

        $hkkaz->setPaginationToken('nextPageToken');

        $this->assertSame('nextPageToken', $hkkaz->getAufsetzpunkt());
    }

    public function testGetMaximaleAnzahlEintraege(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $hkkaz = HKKAZv7::create($kti, false, null, null);
        $hkkaz->maximaleAnzahlEintraege = 100;

        $this->assertSame(100, $hkkaz->getMaximaleAnzahlEintraege());
    }

    public function testGetMaximaleAnzahlEintraegeNull(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $hkkaz = HKKAZv7::create($kti, false, null, null);

        $this->assertNull($hkkaz->getMaximaleAnzahlEintraege());
    }
}
