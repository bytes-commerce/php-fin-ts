<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HKEND;

use BytesCommerce\Segment\HKEND\HKENDv1;

class HKENDv1Test extends \PHPUnit\Framework\TestCase
{
    public const WIRE_EXAMPLE = "HKEND:1:1:3+FAKEDIALOGIDabcdefghijklmnopqr'";

    public function testParse(): void
    {
        $segment = HKENDv1::parse(self::WIRE_EXAMPLE);
        $this->assertEquals(1, $segment->segmentkopf->segmentnummer);
        $this->assertEquals(1, $segment->segmentkopf->segmentversion);
        $this->assertEquals('HKEND', $segment->segmentkopf->segmentkennung);
        $this->assertEquals('FAKEDIALOGIDabcdefghijklmnopqr', $segment->dialogId);
    }

    public function testCreate(): void
    {
        $segment = HKENDv1::create('MYDIALOGID123');
        $this->assertEquals('MYDIALOGID123', $segment->dialogId);
    }

    public function testSerialize(): void
    {
        $segment = HKENDv1::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->dialogId = 'FAKEDIALOGIDabcdefghijklmnopqr';

        $serialized = $segment->serialize();
        $this->assertEquals("HKEND:1:1+FAKEDIALOGIDabcdefghijklmnopqr'", $serialized);
    }

    public function testParseSerializedRoundtrip(): void
    {
        $segment = HKENDv1::parse(self::WIRE_EXAMPLE);
        $serialized = $segment->serialize();
        $parsed = HKENDv1::parse($serialized);
        $this->assertEquals($segment, $parsed);
    }

    public function testValidate(): void
    {
        $segment = HKENDv1::parse(self::WIRE_EXAMPLE);
        $segment->validate();
        $this->assertTrue(true);
    }
}
