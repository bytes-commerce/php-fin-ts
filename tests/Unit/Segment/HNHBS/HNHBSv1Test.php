<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HNHBS;

use BytesCommerce\Segment\HNHBS\HNHBSv1;

class HNHBSv1Test extends \PHPUnit\Framework\TestCase
{
    public const WIRE_EXAMPLE = "HNHBS:5:1+2'";

    public function testParse(): void
    {
        $segment = HNHBSv1::parse(self::WIRE_EXAMPLE);
        $this->assertEquals(5, $segment->segmentkopf->segmentnummer);
        $this->assertEquals(1, $segment->segmentkopf->segmentversion);
        $this->assertEquals('HNHBS', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(2, $segment->nachrichtennummer);
    }

    public function testSerialize(): void
    {
        $segment = HNHBSv1::createEmpty();
        $segment->segmentkopf->segmentnummer = 5;
        $segment->nachrichtennummer = 2;

        $serialized = $segment->serialize();
        $this->assertEquals("HNHBS:5:1+2'", $serialized);
    }

    public function testParseSerializedRoundtrip(): void
    {
        $segment = HNHBSv1::parse(self::WIRE_EXAMPLE);
        $serialized = $segment->serialize();
        $parsed = HNHBSv1::parse($serialized);
        $this->assertEquals($segment, $parsed);
    }

    public function testValidate(): void
    {
        $segment = HNHBSv1::parse(self::WIRE_EXAMPLE);
        $segment->validate();
        $this->assertTrue(true);
    }
}
