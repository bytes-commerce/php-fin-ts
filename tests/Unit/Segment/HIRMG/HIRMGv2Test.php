<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HIRMG;

use BytesCommerce\Segment\HIRMG\HIRMGv2;
use BytesCommerce\Segment\HIRMS\Rueckmeldung;

class HIRMGv2Test extends \PHPUnit\Framework\TestCase
{
    public function testCreateEmpty(): void
    {
        $segment = HIRMGv2::createEmpty();
        $this->assertEquals('HIRMG', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(2, $segment->segmentkopf->segmentversion);
    }

    public function testRueckmeldungArray(): void
    {
        $segment = HIRMGv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $this->assertCount(1, $segment->rueckmeldung);
        $this->assertEquals(20, $segment->rueckmeldung[0]->rueckmeldungscode);
    }

    public function testSerialize(): void
    {
        $segment = HIRMGv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $serialized = $segment->serialize();
        $this->assertStringStartsWith('HIRMG:1:2', $serialized);
    }

    public function testFindRueckmeldungSuccessCode(): void
    {
        $segment = HIRMGv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;

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
        $segment = HIRMGv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $found = $segment->findRueckmeldung(9999);
        $this->assertNull($found);
    }

    public function testValidate(): void
    {
        $segment = HIRMGv2::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;

        $rueckmeldung = new Rueckmeldung();
        $rueckmeldung->rueckmeldungscode = 20;
        $rueckmeldung->rueckmeldungstext = 'OK';
        $segment->rueckmeldung = [$rueckmeldung];

        $segment->validate();
        $this->assertTrue(true);
    }
}
