<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HIRMS;

use BytesCommerce\Segment\HIRMS\HIRMSv2;
use BytesCommerce\Segment\HIRMS\Rueckmeldung;

class HIRMSv2Test extends \PHPUnit\Framework\TestCase
{
    public function testCreateEmpty(): void
    {
        $segment = HIRMSv2::createEmpty();
        $this->assertEquals('HIRMS', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(2, $segment->segmentkopf->segmentversion);
    }

    public function testRueckmeldungArray(): void
    {
        $segment = HIRMSv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 2;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $this->assertCount(1, $segment->rueckmeldung);
        $this->assertEquals(20, $segment->rueckmeldung[0]->rueckmeldungscode);
    }

    public function testSerialize(): void
    {
        $segment = HIRMSv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 2;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $serialized = $segment->serialize();
        $this->assertStringStartsWith('HIRMS:2:2', $serialized);
    }

    public function testFindRueckmeldungByCode(): void
    {
        $segment = HIRMSv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 2;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $found = $segment->findRueckmeldung(20);
        $this->assertNotNull($found);
        $this->assertEquals(20, $found->rueckmeldungscode);
    }

    public function testFindRueckmeldungNotFound(): void
    {
        $segment = HIRMSv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 2;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $found = $segment->findRueckmeldung(9999);
        $this->assertNull($found);
    }

    public function testValidate(): void
    {
        $segment = HIRMSv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 2;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $segment->validate();
        $this->assertTrue(true);
    }
}
