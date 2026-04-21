<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\Syntax;

use BytesCommerce\Syntax\InvalidResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for InvalidResponseException class.
 * Tests the exception thrown when server response is invalid.
 */
class InvalidResponseExceptionTest extends TestCase
{
    public function testConstructorWithMessageOnly(): void
    {
        $invalidResponseException = new InvalidResponseException('Invalid response format');

        $this->assertSame('Invalid response format', $invalidResponseException->getMessage());
        $this->assertSame(0, $invalidResponseException->getCode());
        $this->assertNull($invalidResponseException->getPrevious());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $invalidResponseException = new InvalidResponseException('Invalid response format', 42);

        $this->assertSame('Invalid response format', $invalidResponseException->getMessage());
        $this->assertSame(42, $invalidResponseException->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $runtimeException = new \RuntimeException('Previous error');
        $invalidResponseException = new InvalidResponseException('Invalid response', 42, $runtimeException);

        $this->assertSame('Invalid response', $invalidResponseException->getMessage());
        $this->assertSame(42, $invalidResponseException->getCode());
        $this->assertSame($runtimeException, $invalidResponseException->getPrevious());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $invalidResponseException = new InvalidResponseException('Test');

        $this->assertInstanceOf(\RuntimeException::class, $invalidResponseException);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new InvalidResponseException('Test message', 42);
        } catch (\RuntimeException $runtimeException) {
            $this->assertInstanceOf(InvalidResponseException::class, $runtimeException);
        }
    }
}
