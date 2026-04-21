<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HIBPA;

use BytesCommerce\Segment\HIBPA\HIBPAv3;
use BytesCommerce\Segment\Common\Kik;

class HIBPAv3Test extends \PHPUnit\Framework\TestCase
{
    public function testBpdVersionProperty(): void
    {
        $segment = HIBPAv3::createEmpty();
        $segment->bpdVersion = 5;
        $this->assertEquals(5, $segment->bpdVersion);
    }

    public function testKreditinstitutskennungCreation(): void
    {
        $segment = HIBPAv3::createEmpty();
        $segment->kreditinstitutskennung = Kik::create('12345678');
        $this->assertEquals('12345678', $segment->kreditinstitutskennung->getKreditinstitutscode());
        $this->assertEquals('280', $segment->kreditinstitutskennung->getLaenderkennzeichen());
    }

    public function testKreditinstitutsbezeichnung(): void
    {
        $segment = HIBPAv3::createEmpty();
        $segment->kreditinstitutsbezeichnung = 'Test Bank';
        $this->assertEquals('Test Bank', $segment->kreditinstitutsbezeichnung);
    }

    public function testAnzahlGeschaeftsvorfallarten(): void
    {
        $segment = HIBPAv3::createEmpty();
        $segment->anzahlGeschaeftsvorfallarten = 50;
        $this->assertEquals(50, $segment->anzahlGeschaeftsvorfallarten);
    }

    public function testSegmentkopfInitialization(): void
    {
        $segment = HIBPAv3::createEmpty();
        $this->assertEquals('HIBPA', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(3, $segment->segmentkopf->segmentversion);
    }
}
