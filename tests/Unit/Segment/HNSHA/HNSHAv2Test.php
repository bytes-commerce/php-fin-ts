<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HNSHA;

use BytesCommerce\Segment\HNSHA\HNSHAv2;
use BytesCommerce\Segment\HNSHA\BenutzerdefinierteSignaturV1;

class HNSHAv2Test extends \PHPUnit\Framework\TestCase
{
    public function testSegmentkopfInitialization(): void
    {
        $segment = HNSHAv2::createEmpty();
        $this->assertEquals('HNSHA', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(2, $segment->segmentkopf->segmentversion);
    }

    public function testSicherheitskontrollreferenz(): void
    {
        $segment = HNSHAv2::createEmpty();
        $segment->sicherheitskontrollreferenz = '12345678';
        $this->assertEquals('12345678', $segment->sicherheitskontrollreferenz);
    }

    public function testCreate(): void
    {
        $benutzerdefinierteSignatur = new BenutzerdefinierteSignaturV1();
        $segment = HNSHAv2::create('9999999', $benutzerdefinierteSignatur);
        $this->assertEquals('9999999', $segment->sicherheitskontrollreferenz);
        $this->assertSame($benutzerdefinierteSignatur, $segment->benutzerdefinierteSignatur);
    }

    public function testValidate(): void
    {
        $segment = HNSHAv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->sicherheitskontrollreferenz = '12345678';
        $segment->validate();
        $this->assertTrue(true);
    }
}
