<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\KAZ;

use BytesCommerce\Segment\Common\Kti;
use BytesCommerce\Segment\KAZ\HKKAZv7;
use PHPUnit\Framework\TestCase;

final class HKKAZv7Test extends TestCase
{
    public function testCreate(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $vonDatum = new \DateTime('2024-01-01');
        $bisDatum = new \DateTime('2024-01-31');

        $hkkaZv7 = HKKAZv7::create($kti, false, $vonDatum, $bisDatum);

        $this->assertSame($kti, $hkkaZv7->getKontoverbindungInternational());
        $this->assertFalse($hkkaZv7->getAlleKonten());
        $this->assertSame('20240101', $hkkaZv7->getVonDatum());
        $this->assertSame('20240131', $hkkaZv7->getBisDatum());
    }

    public function testCreateWithAllParameters(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $vonDatum = new \DateTime('2024-01-01');
        $bisDatum = new \DateTime('2024-01-31');
        $aufsetzpunkt = 'token123';

        $hkkaZv7 = HKKAZv7::create($kti, true, $vonDatum, $bisDatum, $aufsetzpunkt);

        $this->assertSame($kti, $hkkaZv7->getKontoverbindungInternational());
        $this->assertTrue($hkkaZv7->getAlleKonten());
        $this->assertSame('20240101', $hkkaZv7->getVonDatum());
        $this->assertSame('20240131', $hkkaZv7->getBisDatum());
        $this->assertSame('token123', $hkkaZv7->getAufsetzpunkt());
    }

    public function testCreateWithNullDates(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');

        $hkkaZv7 = HKKAZv7::create($kti, false, null, null);

        $this->assertNull($hkkaZv7->getVonDatum());
        $this->assertNull($hkkaZv7->getBisDatum());
    }

    public function testSetPaginationToken(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $hkkaZv7 = HKKAZv7::create($kti, false, null, null);

        $hkkaZv7->setPaginationToken('nextPageToken');

        $this->assertSame('nextPageToken', $hkkaZv7->getAufsetzpunkt());
    }

    public function testGetMaximaleAnzahlEintraege(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $hkkaZv7 = HKKAZv7::create($kti, false, null, null);
        $hkkaZv7->maximaleAnzahlEintraege = 100;

        $this->assertSame(100, $hkkaZv7->getMaximaleAnzahlEintraege());
    }

    public function testGetMaximaleAnzahlEintraegeNull(): void
    {
        $kti = Kti::create('DE89370400440532013000', 'COBADEFFXXX');
        $hkkaZv7 = HKKAZv7::create($kti, false, null, null);

        $this->assertNull($hkkaZv7->getMaximaleAnzahlEintraege());
    }
}
