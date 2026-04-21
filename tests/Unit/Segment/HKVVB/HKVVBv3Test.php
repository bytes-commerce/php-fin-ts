<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Segment\HKVVB;

use BytesCommerce\Options\FinTsOptions;
use BytesCommerce\Options\Credentials;
use BytesCommerce\Protocol\BPD;
use BytesCommerce\Protocol\UPD;
use BytesCommerce\Segment\HKVVB\HKVVBv3;

class HKVVBv3Test extends \PHPUnit\Framework\TestCase
{
    public const WIRE_EXAMPLE = "HKVVB:1:3:3+5+3+0+TestProduct+1.0'";

    public function testParse(): void
    {
        $segment = HKVVBv3::parse(self::WIRE_EXAMPLE);
        $this->assertEquals(1, $segment->segmentkopf->segmentnummer);
        $this->assertEquals(3, $segment->segmentkopf->segmentversion);
        $this->assertEquals('HKVVB', $segment->segmentkopf->segmentkennung);
        $this->assertEquals(5, $segment->bpdVersion);
        $this->assertEquals(3, $segment->updVersion);
        $this->assertEquals(0, $segment->dialogsprache);
        $this->assertEquals('TestProduct', $segment->produktbezeichnung);
        $this->assertEquals('1.0', $segment->produktversion);
    }

    public function testCreateWithBpdAndUpd(): void
    {
        $options = new FinTsOptions();
        $options->productName = 'TestProduct';
        $options->productVersion = '2.0';

        $bpd = $this->createMock(BPD::class);
        $bpd->method('getVersion')->willReturn(7);

        $upd = $this->createMock(UPD::class);
        $upd->method('getVersion')->willReturn(4);

        $segment = HKVVBv3::create($options, $bpd, $upd);
        $this->assertEquals(7, $segment->bpdVersion);
        $this->assertEquals(4, $segment->updVersion);
        $this->assertEquals('TestProduct', $segment->produktbezeichnung);
        $this->assertEquals('2.0', $segment->produktversion);
    }

    public function testCreateWithNullBpdAndUpd(): void
    {
        $options = new FinTsOptions();
        $options->productName = 'TestProduct';
        $options->productVersion = '1.0';

        $segment = HKVVBv3::create($options, null, null);
        $this->assertEquals(0, $segment->bpdVersion);
        $this->assertEquals(0, $segment->updVersion);
    }

    public function testSerialize(): void
    {
        $segment = HKVVBv3::createEmpty();
        $segment->segmentkopf->segmentnummer = 1;
        $segment->bpdVersion = 5;
        $segment->updVersion = 3;
        $segment->dialogsprache = 0;
        $segment->produktbezeichnung = 'TestProduct';
        $segment->produktversion = '1.0';

        $serialized = $segment->serialize();
        $this->assertEquals("HKVVB:1:3+5+3+0+TestProduct+1.0'", $serialized);
    }

    public function testParseSerializedRoundtrip(): void
    {
        $segment = HKVVBv3::parse(self::WIRE_EXAMPLE);
        $serialized = $segment->serialize();
        $parsed = HKVVBv3::parse($serialized);
        $this->assertEquals($segment, $parsed);
    }

    public function testValidate(): void
    {
        $segment = HKVVBv3::parse(self::WIRE_EXAMPLE);
        $segment->validate();
        $this->assertTrue(true);
    }
}
