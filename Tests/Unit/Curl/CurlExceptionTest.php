<?php

namespace Fhp\Tests\Unit\Curl;

use Fhp\CurlException;
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

        $exception = new CurlException($message, $response, $code, $curlInfo, $curlMessage);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($curlInfo, $exception->getCurlInfo());
        $this->assertSame($curlMessage, $exception->getCurlMessage());
    }

    public function testConstructorWithMinimalParameters(): void
    {
        $exception = new CurlException('Simple error', null);

        $this->assertSame('Simple error', $exception->getMessage());
        $this->assertNull($exception->getResponse());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame([], $exception->getCurlInfo());
        $this->assertNull($exception->getCurlMessage());
    }

    public function testConstructorWithNullResponse(): void
    {
        $exception = new CurlException('Error', null, 0, [], null);

        $this->assertNull($exception->getResponse());
        $this->assertNull($exception->getCurlMessage());
    }

    public function testGetResponseReturnsCorrectValue(): void
    {
        $response = 'Base64 encoded response data';
        $exception = new CurlException('Test', $response);

        $this->assertSame($response, $exception->getResponse());
    }

    public function testGetCurlInfoReturnsCorrectValue(): void
    {
        $curlInfo = [
            'http_code' => 200,
            'url' => 'https://example.com',
            'total_time' => 0.5,
        ];
        $exception = new CurlException('Test', null, 0, $curlInfo);

        $this->assertSame($curlInfo, $exception->getCurlInfo());
    }

    public function testGetCurlInfoReturnsEmptyArrayByDefault(): void
    {
        $exception = new CurlException('Test', null);

        $this->assertSame([], $exception->getCurlInfo());
    }

    public function testGetCurlMessageReturnsCorrectValue(): void
    {
        $curlMessage = 'SSL certificate problem';
        $exception = new CurlException('Test', null, 0, [], $curlMessage);

        $this->assertSame($curlMessage, $exception->getCurlMessage());
    }

    public function testGetCurlMessageReturnsNullByDefault(): void
    {
        $exception = new CurlException('Test', null);

        $this->assertNull($exception->getCurlMessage());
    }

    public function testInheritsFromException(): void
    {
        $exception = new CurlException('Test', null);

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new CurlException('Connection failed', null, 0, [], 'Could not resolve host');
        } catch (\Exception $e) {
            $this->assertInstanceOf(CurlException::class, $e);
        }
    }

    public function testCodePassthrough(): void
    {
        $code = 7; // CURLE_COULDNT_CONNECT
        $exception = new CurlException('Connection failed', null, $code);

        $this->assertSame($code, $exception->getCode());
    }

    public function testAllFieldsAreAccessible(): void
    {
        $message = 'Full error message';
        $response = 'Error details';
        $code = 28; // CURLE_OPERATION_TIMEDOUT
        $curlInfo = ['http_code' => 408];
        $curlMessage = 'Operation timeout';

        $exception = new CurlException($message, $response, $code, $curlInfo, $curlMessage);

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame($response, $exception->getResponse());
        $this->assertSame($code, $exception->getCode());
        $this->assertSame($curlInfo, $exception->getCurlInfo());
        $this->assertSame($curlMessage, $exception->getCurlMessage());
    }
}
