<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HNSHK;

use BytesCommerce\Segment\HNSHK\HNSHKv4;

class HNSHKv4Test extends \PHPUnit\Framework\TestCase
{
    public function testSegmentkopfInitialization(): void
    {
        $segment = HNSHKv4::createEmpty();
        $this->assertEquals('HNSHK', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(4, $segment->segmentkopf->segmentversion);
    }

    public function testSicherheitskontrollreferenz(): void
    {
        $segment = HNSHKv4::createEmpty();
        $segment->sicherheitskontrollreferenz = '12345678';
        $this->assertEquals('12345678', $segment->sicherheitskontrollreferenz);
    }

    public function testBereichDerSicherheitsapplikationDefault(): void
    {
        $segment = HNSHKv4::createEmpty();
        // Default is 1 (Signaturkopf und HBCI-Nutzdaten)
        $this->assertEquals(1, $segment->bereichDerSicherheitsapplikation);
    }

    public function testRolleDesSicherheitslieferantenDefault(): void
    {
        $segment = HNSHKv4::createEmpty();
        // Default is 1 (Herausgeber der signierten Nachricht)
        $this->assertEquals(1, $segment->rolleDesSicherheitslieferanten);
    }
}
