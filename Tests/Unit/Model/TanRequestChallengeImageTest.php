<?php

namespace Fhp\Tests\Unit\Model;

use Fhp\Model\TanRequestChallengeImage;
use Fhp\Syntax\Bin;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for TanRequestChallengeImage class.
 * Tests parsing of binary image challenge data.
 */
class TanRequestChallengeImageTest extends TestCase
{
    public function testConstructorWithValidImageData(): void
    {
        // Format: 2 bytes MIME length + MIME type + 2 bytes data length + data
        $mimeType = 'image/png';
        $data = 'fake-image-data';

        // Build binary data
        $mimeTypeLength = strlen($mimeType);
        $mimeLengthBytes = chr(($mimeTypeLength >> 8) & 0xFF) . chr($mimeTypeLength & 0xFF);

        $dataLength = strlen($data);
        $dataLengthBytes = chr(($dataLength >> 8) & 0xFF) . chr($dataLength & 0xFF);

        $binaryData = $mimeLengthBytes . $mimeType . $dataLengthBytes . $data;
        $bin = new Bin($binaryData);

        $image = new TanRequestChallengeImage($bin);

        $this->assertSame($mimeType, $image->getMimeType());
        $this->assertSame($data, $image->getData());
    }

    public function testConstructorWithJpegImage(): void
    {
        $mimeType = 'image/jpeg';
        $data = 'jpeg-data';

        $mimeTypeLength = strlen($mimeType);
        $mimeLengthBytes = chr(($mimeTypeLength >> 8) & 0xFF) . chr($mimeTypeLength & 0xFF);

        $dataLength = strlen($data);
        $dataLengthBytes = chr(($dataLength >> 8) & 0xFF) . chr($dataLength & 0xFF);

        $binaryData = $mimeLengthBytes . $mimeType . $dataLengthBytes . $data;
        $bin = new Bin($binaryData);

        $image = new TanRequestChallengeImage($bin);

        $this->assertSame($mimeType, $image->getMimeType());
        $this->assertSame($data, $image->getData());
    }

    public function testConstructorWithEmptyDataThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid TAN challenge. Expected image MIME type but only found 0 bytes.');

        new TanRequestChallengeImage(new Bin(''));
    }

    public function testConstructorWithOnlyOneByteThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid TAN challenge. Expected image MIME type but only found 1 bytes.');

        new TanRequestChallengeImage(new Bin('A'));
    }

    public function testConstructorWithMismatchedDataLengthThrowsException(): void
    {
        // MIME type length = 10, but actual data is shorter
        $binaryData = "\x00\x0A" . 'image/png'; // says 10 bytes MIME but only gives 5

        $bin = new Bin($binaryData);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid TAN challenge. Expected image MIME type of length 10 but only found');

        new TanRequestChallengeImage($bin);
    }

    public function testConstructorWithMismatchedDataPayloadLengthThrowsException(): void
    {
        // MIME type = 'image/png' (10 bytes)
        // Data length says 100 but actual data is only 5 bytes
        $mimeType = 'image/png';
        $mimeTypeLength = strlen($mimeType);
        $mimeLengthBytes = chr(($mimeTypeLength >> 8) & 0xFF) . chr($mimeTypeLength & 0xFF);

        // Claim 100 bytes of data but provide less
        $dataLengthBytes = "\x00\x64"; // 100 in big endian

        $binaryData = $mimeLengthBytes . $mimeType . $dataLengthBytes . 'short';

        $bin = new Bin($binaryData);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unexpected data length, expected 100 but found');

        new TanRequestChallengeImage($bin);
    }

    public function testGetMimeTypeReturnsCorrectType(): void
    {
        $mimeType = 'image/gif';
        $data = 'gif-data';

        $mimeTypeLength = strlen($mimeType);
        $mimeLengthBytes = chr(($mimeTypeLength >> 8) & 0xFF) . chr($mimeTypeLength & 0xFF);

        $dataLength = strlen($data);
        $dataLengthBytes = chr(($dataLength >> 8) & 0xFF) . chr($dataLength & 0xFF);

        $binaryData = $mimeLengthBytes . $mimeType . $dataLengthBytes . $data;
        $bin = new Bin($binaryData);

        $image = new TanRequestChallengeImage($bin);

        $this->assertSame('image/gif', $image->getMimeType());
    }

    public function testGetDataReturnsExactData(): void
    {
        $mimeType = 'image/png';
        $data = 'exact-data-payload';

        $mimeTypeLength = strlen($mimeType);
        $mimeLengthBytes = chr(($mimeTypeLength >> 8) & 0xFF) . chr($mimeTypeLength & 0xFF);

        $dataLength = strlen($data);
        $dataLengthBytes = chr(($dataLength >> 8) & 0xFF) . chr($dataLength & 0xFF);

        $binaryData = $mimeLengthBytes . $mimeType . $dataLengthBytes . $data;
        $bin = new Bin($binaryData);

        $image = new TanRequestChallengeImage($bin);

        $this->assertSame($data, $image->getData());
    }

    public function testConstructorWithLargeDataPayload(): void
    {
        $mimeType = 'image/png';
        $data = str_repeat('x', 1000);

        $mimeTypeLength = strlen($mimeType);
        $mimeLengthBytes = chr(($mimeTypeLength >> 8) & 0xFF) . chr($mimeTypeLength & 0xFF);

        $dataLength = strlen($data);
        $dataLengthBytes = chr(($dataLength >> 8) & 0xFF) . chr($dataLength & 0xFF);

        $binaryData = $mimeLengthBytes . $mimeType . $dataLengthBytes . $data;
        $bin = new Bin($binaryData);

        $image = new TanRequestChallengeImage($bin);

        $this->assertSame(1000, strlen($image->getData()));
    }
}
