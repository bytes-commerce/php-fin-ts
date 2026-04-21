<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HNHBK;

use BytesCommerce\Segment\HNHBK\HNHBKv3;

class HNHBKv3Test extends \PHPUnit\Framework\TestCase
{
    public const WIRE_EXAMPLE = "HNHBK:1:3+000000000150+300+FAKEDIALOGIDabcdefghijklmnopqr+2'";

    public function testParse(): void
    {
        $segment = HNHBKv3::parse(self::WIRE_EXAMPLE);
        $this->assertEquals(1, $segment->segmentkopf->segmentnummer);
        $this->assertEquals(3, $segment->segmentkopf->segmentversion);
        $this->assertEquals('HNHBK', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(150, $segment->getNachrichtengroesse());
        $this->assertEquals(300, $segment->hbciVersion);
        $this->assertEquals('FAKEDIALOGIDabcdefghijklmnopqr', $segment->dialogId);
        $this->assertEquals(2, $segment->nachrichtennummer);
    }

    public function testSerialize(): void
    {
        $segment = HNHBKv3::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->setNachrichtengroesse(150);
        $segment->hbciVersion = 300;
        $segment->dialogId = 'FAKEDIALOGIDabcdefghijklmnopqr';
        $segment->nachrichtennummer = 2;

        $serialized = $segment->serialize();
        $this->assertEquals("HNHBK:1:3+000000000150+300+FAKEDIALOGIDabcdefghijklmnopqr+2'", $serialized);
    }

    public function testParseSerializedRoundtrip(): void
    {
        $segment = HNHBKv3::parse(self::WIRE_EXAMPLE);
        $serialized = $segment->serialize();
        $parsed = HNHBKv3::parse($serialized);
        $this->assertEquals($segment, $parsed);
    }

    public function testSetNachrichtengroesse(): void
    {
        $segment = HNHBKv3::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->setNachrichtengroesse(123456789);
        $this->assertEquals('000123456789', $segment->nachrichtengroesse);
    }

    public function testValidate(): void
    {
        $segment = HNHBKv3::parse(self::WIRE_EXAMPLE);
        $segment->validate(); // Should not throw
        $this->assertTrue(true); // Verify validate() completed without exception
    }

    public function testHibcVersionConstant(): void
    {
        // FinTS 3.0 uses hbciVersion 300
        $this->assertEquals(300, HNHBKv3::createEmpty()->hbciVersion);
    }
}
