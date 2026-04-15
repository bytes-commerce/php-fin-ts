<?php

namespace Fhp\Tests\Unit\Segment\DSE;

use Fhp\Segment\DSE\MinimaleVorlaufzeitSEPALastschrift;
use PHPUnit\Framework\TestCase;

final class MinimaleVorlaufzeitSEPALastschriftTest extends TestCase
{
    public function testCreateWithAllParameters(): void
    {
        $result = MinimaleVorlaufzeitSEPALastschrift::create(5, '130000', 0, 1);

        $this->assertSame(5, $result->getMinimaleSEPAVorlaufzeit());
        $this->assertSame('130000', $result->getCutOffZeit());
        $this->assertSame(0, $result->getUnterstuetzteSEPALastschriftartenCodiert());
        $this->assertSame(1, $result->getSequenceTypeCodiert());
    }

    public function testCreateWithOnlyRequiredParameters(): void
    {
        $result = MinimaleVorlaufzeitSEPALastschrift::create(3, '120000', 0, 0);

        $this->assertSame(3, $result->getMinimaleSEPAVorlaufzeit());
        $this->assertSame('120000', $result->getCutOffZeit());
    }

    public function testCreateWithDifferentMinTage(): void
    {
        $result = MinimaleVorlaufzeitSEPALastschrift::create(1, '080000', 0, 0);

        $this->assertSame(1, $result->getMinimaleSEPAVorlaufzeit());
    }

    public function testCreateWithDifferentCutOffZeit(): void
    {
        $result = MinimaleVorlaufzeitSEPALastschrift::create(3, '150000', 0, 0);

        $this->assertSame('150000', $result->getCutOffZeit());
    }

    public function testParseCodedWithValidInput(): void
    {
        $coded = '0;0;3;120000';

        $result = MinimaleVorlaufzeitSEPALastschrift::parseCoded($coded);

        $this->assertArrayHasKey('CORE', $result);
        $this->assertArrayHasKey('FNAL', $result['CORE']);
        $this->assertInstanceOf(MinimaleVorlaufzeitSEPALastschrift::class, $result['CORE']['FNAL']);
        $this->assertSame(3, $result['CORE']['FNAL']->getMinimaleSEPAVorlaufzeit());
        $this->assertSame('120000', $result['CORE']['FNAL']->getCutOffZeit());
    }

    public function testParseCodedWithMultipleChunks(): void
    {
        $coded = '0;0;3;120000;1;1;5;130000';

        $result = MinimaleVorlaufzeitSEPALastschrift::parseCoded($coded);

        $this->assertArrayHasKey('CORE', $result);
        $this->assertArrayHasKey('FNAL', $result['CORE']);

        $this->assertArrayHasKey('COR1', $result);
        $this->assertArrayHasKey('RCUR', $result['COR1']);
    }

    public function testParseCodedWithUnknownIndices(): void
    {
        $coded = '99;99;3;120000';

        $result = MinimaleVorlaufzeitSEPALastschrift::parseCoded($coded);

        $this->assertEmpty($result);
    }

    public function testUnterstuetzteSepaLastschriftartenCodedConstants(): void
    {
        $constants = MinimaleVorlaufzeitSEPALastschrift::UNTERSTUETZTE_SEPA_LASTSCHRIFTARTEN_CODIERT;

        $this->assertEquals(['CORE'], $constants[0]);
        $this->assertEquals(['COR1'], $constants[1]);
        $this->assertEquals(['CORE', 'COR1'], $constants[2]);
    }

    public function testSequenceTypeCodedConstants(): void
    {
        $constants = MinimaleVorlaufzeitSEPALastschrift::SEQUENCE_TYPE_CODIERT;

        $this->assertSame(['FNAL', 'RCUR', 'FRST', 'OOFF'], $constants[0]);
        $this->assertSame(['FNAL', 'RCUR'], $constants[1]);
        $this->assertSame(['FRST', 'OOFF'], $constants[2]);
    }
}
