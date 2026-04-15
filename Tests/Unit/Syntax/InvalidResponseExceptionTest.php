<?php

namespace Fhp\Tests\Unit\Syntax;

use Fhp\Syntax\InvalidResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for InvalidResponseException class.
 * Tests the exception thrown when server response is invalid.
 */
class InvalidResponseExceptionTest extends TestCase
{
    public function testConstructorWithMessageOnly(): void
    {
        $exception = new InvalidResponseException('Invalid response format');

        $this->assertSame('Invalid response format', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $exception = new InvalidResponseException('Invalid response format', 42);

        $this->assertSame('Invalid response format', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $previous = new \RuntimeException('Previous error');
        $exception = new InvalidResponseException('Invalid response', 42, $previous);

        $this->assertSame('Invalid response', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $exception = new InvalidResponseException('Test');

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new InvalidResponseException('Test message', 42);
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(InvalidResponseException::class, $e);
        }
    }
}
