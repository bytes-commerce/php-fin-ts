<?php

declare(strict_types=1);



namespace BytesCommerce\Model;

use BytesCommerce\Syntax\Bin;

class TanRequestChallengeImage
{
    private string $mimeType;

    private string $data;

    public function __construct(Bin $bin)
    {
        $data = $bin->getData();

        // Documentation: https://www.hbci-zka.de/dokumente/spezifikation_deutsch/hhd/Belegungsrichtlinien%20TANve1.5%20FV%20vom%202018-04-16.pdf
        // II.3

        // Matrix-Format:
        // 2 bytes = length of mime type
        // mime type as string
        // 2 bytes = length of data

        $dataLength = strlen($data);
        if ($dataLength < 2) {
            throw new \InvalidArgumentException(
                sprintf('Invalid TAN challenge. Expected image MIME type but only found %d bytes. ', $dataLength));
        }

        $mimeTypeLengthString = substr($data, 0, 2);
        $mimeTypeLength = ord($mimeTypeLengthString[0]) * 256 + ord($mimeTypeLengthString[1]);

        if ($dataLength < 2 + $mimeTypeLength + 2) {
            throw new \InvalidArgumentException(
                sprintf('Invalid TAN challenge. Expected image MIME type of length %d but only found %d bytes. ', $mimeTypeLength, $dataLength) .
                'Maybe the challenge is not an image but rather a URL or a flicker code.');
        }

        $this->mimeType = substr($data, 2, $mimeTypeLength);

        $data = substr($data, 2 + $mimeTypeLength);

        $dataLengthString = substr($data, 0, 2);
        $expectedDataLength = ord($dataLengthString[0]) * 256 + ord($dataLengthString[1]);
        $actualDataLength = strlen($data) - 2;

        if ($expectedDataLength !== $actualDataLength) {
            // This exception is thrown, if there is an encoding problem
            // f.e.: the serialized action was saved as a string, but not base64 encoded
            throw new \InvalidArgumentException(
                sprintf('Unexpected data length, expected %d but found %d bytes.', $expectedDataLength, $actualDataLength));
        }

        $this->data = substr($data, 2, $expectedDataLength);
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getData(): string
    {
        return $this->data;
    }
}
