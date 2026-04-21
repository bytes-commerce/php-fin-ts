<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HKIDN;

use BytesCommerce\Options\Credentials;
use BytesCommerce\Segment\HKIDN\HKIDNv2;

class HKIDNv2Test extends \PHPUnit\Framework\TestCase
{
    public function testSegmentkopfInitialization(): void
    {
        $segment = HKIDNv2::createEmpty();
        $this->assertEquals('HKIDN', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(2, $segment->segmentkopf->segmentversion);
    }

    public function testCreate(): void
    {
        $credentials = Credentials::create('testuser', '12345');
        $segment = HKIDNv2::create('12345678', $credentials, 'KUNDENSYSTEMID12345');
        $this->assertEquals('12345678', $segment->kreditinstitutskennung->getKreditinstitutscode());
        $this->assertEquals('testuser', $segment->kundenId);
        $this->assertEquals('KUNDENSYSTEMID12345', $segment->kundensystemId);
        $this->assertEquals(1, $segment->kundensystemStatus);
    }

    public function testCreateAnonymous(): void
    {
        $segment = HKIDNv2::createAnonymous('12345678');
        $this->assertEquals('12345678', $segment->kreditinstitutskennung->getKreditinstitutscode());
        $this->assertEquals(HKIDNv2::ANONYMOUS_KUNDEN_ID, $segment->kundenId);
        $this->assertEquals(HKIDNv2::MISSING_KUNDENSYSTEM_ID, $segment->kundensystemId);
        $this->assertEquals(0, $segment->kundensystemStatus);
    }

    public function testValidate(): void
    {
        $segment = HKIDNv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->kreditinstitutskennung = \BytesCommerce\Segment\Common\Kik::create('12345678');
        $segment->kundenId = 'testuser';
        $segment->kundensystemId = 'KUNDENSYSTEMID12345';
        $segment->kundensystemStatus = 1;
        $segment->validate();
        $this->assertTrue(true);
    }

    public function testAnonymousKundenIdConstant(): void
    {
        $this->assertEquals('9999999999', HKIDNv2::ANONYMOUS_KUNDEN_ID);
    }

    public function testMissingKundensystemIdConstant(): void
    {
        $this->assertEquals('0', HKIDNv2::MISSING_KUNDENSYSTEM_ID);
    }
}
