<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HISYN;

use BytesCommerce\Segment\HISYN\HISYNv4;

class HISYNv4Test extends \PHPUnit\Framework\TestCase
{
    public function testSegmentkopfInitialization(): void
    {
        $segment = HISYNv4::createEmpty();
        $this->assertEquals('HISYN', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(4, $segment->segmentkopf->segmentversion);
    }

    public function testKundensystemIdProperty(): void
    {
        $segment = HISYNv4::createEmpty();
        $segment->kundensystemId = 'KUNDENSYSTEMID12345678901234567890';
        $this->assertEquals('KUNDENSYSTEMID12345678901234567890', $segment->kundensystemId);
    }

    public function testNachrichtennummerProperty(): void
    {
        $segment = HISYNv4::createEmpty();
        $segment->nachrichtennummer = 1;
        $this->assertEquals(1, $segment->nachrichtennummer);
    }

    public function testValidate(): void
    {
        $segment = HISYNv4::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->kundensystemId = 'KUNDENSYSTEMID12345678901234567890';
        $segment->validate();
        $this->assertTrue(true);
    }
}
