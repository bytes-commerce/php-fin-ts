<?php

namespace BytesCommerce\Tests\Unit\Model\FlickerTan;

use BytesCommerce\Model\FlickerTan\DataElement;
use PHPUnit\Framework\TestCase;

class DataElementTest extends TestCase
{
    public function testParseNextBlockWithEmptyString(): void
    {
        $result = DataElement::parseNextBlock('');

        $this->assertSame('', $result[0]);
        $this->assertInstanceOf(DataElement::class, $result[1]);
    }

    public function testParseNextBlockWithValidData(): void
    {
        $result = DataElement::parseNextBlock('0512345');

        $this->assertSame('', $result[0]);
        $this->assertInstanceOf(DataElement::class, $result[1]);
    }

    public function testParseNextBlockWithRemainingData(): void
    {
        $result = DataElement::parseNextBlock('051234500');

        $this->assertSame('00', $result[0]);
        $this->assertInstanceOf(DataElement::class, $result[1]);
    }

    public function testParseNextBlockWithInvalidLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Parsing went wrong');

        DataElement::parseNextBlock('05123');
    }

    public function testHexToByte(): void
    {
        $result = DataElement::hexToByte('FF', 8);

        $this->assertSame('11111111', $result);
    }

    public function testHexToByteWithShortHex(): void
    {
        $result = DataElement::hexToByte('0', 8);

        $this->assertSame('00000000', $result);
    }

    public function testHexToByteWithLength(): void
    {
        $result = DataElement::hexToByte('A', 4);

        $this->assertSame('1010', $result);
    }

    public function testConstants(): void
    {
        $this->assertSame('1', DataElement::ENC_ASCII);
        $this->assertSame('0', DataElement::ENC_BCD);
        $this->assertSame(DataElement::ENC_ASCII, DataElement::ENC_ASC);
    }

    public function testToHexWithEmptyData(): void
    {
        $result = DataElement::parseNextBlock('');

        $dataElement = $result[1];
        $this->assertSame('', $dataElement->toHex());
    }
}
