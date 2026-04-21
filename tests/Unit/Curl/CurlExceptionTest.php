<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Curl;

use BytesCommerce\CurlException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for CurlException class.
 * Tests the exception thrown when cURL operations fail.
 */
class CurlExceptionTest extends TestCase
{
    public function testConstructorWithAllParameters(): void
    {
        $message = 'Failed sending request';
        $response = 'Error response body';
        $code = 42;
        $curlInfo = ['http_code' => 500, 'url' => 'https://testbank.de'];
        $curlMessage = 'Could not connect to host';

        $curlException = new CurlException($message, $response, $code, $curlInfo, $curlMessage);

        $this->assertSame($message, $curlException->getMessage());
        $this->assertSame($response, $curlException->getResponse());
        $this->assertSame($code, $curlException->getCode());
        $this->assertSame($curlInfo, $curlException->getCurlInfo());
        $this->assertSame($curlMessage, $curlException->getCurlMessage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $curlException = new CurlException('Simple error', null);

        $this->assertSame('Simple error', $curlException->getMessage());
        $this->assertNull($curlException->getResponse());
        $this->assertSame(0, $curlException->getCode());
        $this->assertSame([], $curlException->getCurlInfo());
        $this->assertNull($curlException->getCurlMessage());
    }

    public function testConstructorWithNullResponse(): void
    {
        $curlException = new CurlException('Error', null, 0, []);

        $this->assertNull($curlException->getResponse());
        $this->assertNull($curlException->getCurlMessage());
    }

    public function testGetResponseReturnsCorrectValue(): void
    {
        $response = 'Base64 encoded response data';
        $curlException = new CurlException('Test', $response);

        $this->assertSame($response, $curlException->getResponse());
    }

    public function testGetCurlInfoReturnsCorrectValue(): void
    {
        $curlInfo = [
            'http_code' => 200,
            'url' => 'https://example.com',
            'total_time' => 0.5,
        ];
        $curlException = new CurlException('Test', null, 0, $curlInfo);

        $this->assertSame($curlInfo, $curlException->getCurlInfo());
    }

    public function testGetCurlInfoReturnsEmptyArrayByDefault(): void
    {
        $curlException = new CurlException('Test', null);

        $this->assertSame([], $curlException->getCurlInfo());
    }

    public function testGetCurlMessageReturnsCorrectValue(): void
    {
        $curlMessage = 'SSL certificate problem';
        $curlException = new CurlException('Test', null, 0, [], $curlMessage);

        $this->assertSame($curlMessage, $curlException->getCurlMessage());
    }

    public function testGetCurlMessageReturnsNullByDefault(): void
    {
        $curlException = new CurlException('Test', null);

        $this->assertNull($curlException->getCurlMessage());
    }

    public function testInheritsFromException(): void
    {
        $curlException = new CurlException('Test', null);

        $this->assertInstanceOf(\Exception::class, $curlException);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new CurlException('Connection failed', null, 0, [], 'Could not resolve host');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(CurlException::class, $exception);
        }
    }

    public function testCodePassthrough(): void
    {
        $code = 7; // CURLE_COULDNT_CONNECT
        $curlException = new CurlException('Connection failed', null, $code);

        $this->assertSame($code, $curlException->getCode());
    }

    public function testAllFieldsAreAccessible(): void
    {
        $message = 'Full error message';
        $response = 'Error details';
        $code = 28; // CURLE_OPERATION_TIMEDOUT
        $curlInfo = ['http_code' => 408];
        $curlMessage = 'Operation timeout';

        $curlException = new CurlException($message, $response, $code, $curlInfo, $curlMessage);

        $this->assertSame($message, $curlException->getMessage());
        $this->assertSame($response, $curlException->getResponse());
        $this->assertSame($code, $curlException->getCode());
        $this->assertSame($curlInfo, $curlException->getCurlInfo());
        $this->assertSame($curlMessage, $curlException->getCurlMessage());
    }
}
