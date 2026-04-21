<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Protocol;

use BytesCommerce\Protocol\UnexpectedResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UnexpectedResponseException class.
 * Tests the exception thrown when server response is unexpected.
 */
class UnexpectedResponseExceptionTest extends TestCase
{
    public function testConstructorWithMessageOnly(): void
    {
        $unexpectedResponseException = new UnexpectedResponseException('Test message');

        $this->assertSame('Test message', $unexpectedResponseException->getMessage());
        $this->assertSame(0, $unexpectedResponseException->getCode());
        $this->assertNull($unexpectedResponseException->getPrevious());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $unexpectedResponseException = new UnexpectedResponseException('Test message', 42);

        $this->assertSame('Test message', $unexpectedResponseException->getMessage());
        $this->assertSame(42, $unexpectedResponseException->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $runtimeException = new \RuntimeException('Previous exception');
        $unexpectedResponseException = new UnexpectedResponseException('Test message', 42, $runtimeException);

        $this->assertSame('Test message', $unexpectedResponseException->getMessage());
        $this->assertSame(42, $unexpectedResponseException->getCode());
        $this->assertSame($runtimeException, $unexpectedResponseException->getPrevious());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $unexpectedResponseException = new UnexpectedResponseException('Test');

        $this->assertInstanceOf(\RuntimeException::class, $unexpectedResponseException);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new UnexpectedResponseException('Test message', 42);
        } catch (\RuntimeException $runtimeException) {
            $this->assertInstanceOf(UnexpectedResponseException::class, $runtimeException);
        }
    }

    public function testExceptionMessagePassthrough(): void
    {
        $message = 'This is an unexpected response from the bank';
        $unexpectedResponseException = new UnexpectedResponseException($message);

        $this->assertSame($message, $unexpectedResponseException->getMessage());
    }

    public function testExceptionCodePassthrough(): void
    {
        $code = 123;
        $unexpectedResponseException = new UnexpectedResponseException('Test', $code);

        $this->assertSame($code, $unexpectedResponseException->getCode());
    }
}
