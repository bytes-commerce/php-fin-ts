<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment;

use BytesCommerce\Segment\SPA\HKSPAv2;
use PHPUnit\Framework\TestCase;

/**
 * Among other things, this test covers the serialization of Bin values.
 */
class HKSPATest extends TestCase
{
    public function testSerialize(): void
    {
        $hkspAv2 = HKSPAv2::createEmpty();
        $hkspAv2->setSegmentNumber(42);
        $hkspAv2->kontoverbindung = [];
        $this->assertEquals("HKSPA:42:2'", $hkspAv2->serialize());
    }
}
