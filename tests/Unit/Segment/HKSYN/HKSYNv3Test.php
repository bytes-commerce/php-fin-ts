<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HKSYN;

use BytesCommerce\Segment\HKSYN\HKSYNv3;

class HKSYNv3Test extends \PHPUnit\Framework\TestCase
{
    public const WIRE_EXAMPLE = "HKSYN:1:3:4+0'";

    public const WIRE_EXAMPLE_MODE_1 = "HKSYN:1:3:4+1'";

    public const WIRE_EXAMPLE_MODE_2 = "HKSYN:1:3:4+2'";

    public function testParseDefaultMode(): void
    {
        $segment = HKSYNv3::parse(self::WIRE_EXAMPLE);
        $this->assertEquals(1, $segment->segmentkopf->segmentnummer);
        $this->assertEquals(3, $segment->segmentkopf->segmentversion);
        $this->assertEquals('HKSYN', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(0, $segment->synchronisierungsmodus);
    }

    public function testParseMode1(): void
    {
        $segment = HKSYNv3::parse(self::WIRE_EXAMPLE_MODE_1);
        $this->assertEquals(1, $segment->synchronisierungsmodus);
    }

    public function testParseMode2(): void
    {
        $segment = HKSYNv3::parse(self::WIRE_EXAMPLE_MODE_2);
        $this->assertEquals(2, $segment->synchronisierungsmodus);
    }

    public function testSerialize(): void
    {
        $segment = HKSYNv3::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->synchronisierungsmodus = 0;

        $serialized = $segment->serialize();
        $this->assertEquals("HKSYN:1:3+0'", $serialized);
    }

    public function testParseSerializedRoundtrip(): void
    {
        $segment = HKSYNv3::parse(self::WIRE_EXAMPLE);
        $serialized = $segment->serialize();
        $parsed = HKSYNv3::parse($serialized);
        $this->assertEquals($segment, $parsed);
    }

    public function testValidate(): void
    {
        $segment = HKSYNv3::parse(self::WIRE_EXAMPLE);
        $segment->validate();
        $this->assertTrue(true);
    }

    public function testSynchronisierungsmodusConstants(): void
    {
        // Verify the default mode is 0 (new Kundensystem-ID)
        $segment = HKSYNv3::createEmpty();
        $this->assertEquals(0, $segment->synchronisierungsmodus);
    }
}
