<?php

namespace Fhp\Tests\Unit\Protocol;

use Fhp\Protocol\UnexpectedResponseException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UnexpectedResponseException class.
 * Tests the exception thrown when server response is unexpected.
 */
class UnexpectedResponseExceptionTest extends TestCase
{
    public function testConstructorWithMessageOnly(): void
    {
        $exception = new UnexpectedResponseException('Test message');

        $this->assertSame('Test message', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $exception = new UnexpectedResponseException('Test message', 42);

        $this->assertSame('Test message', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $previous = new \RuntimeException('Previous exception');
        $exception = new UnexpectedResponseException('Test message', 42, $previous);

        $this->assertSame('Test message', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $exception = new UnexpectedResponseException('Test');

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new UnexpectedResponseException('Test message', 42);
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(UnexpectedResponseException::class, $e);
        }
    }

    public function testExceptionMessagePassthrough(): void
    {
        $message = 'This is an unexpected response from the bank';
        $exception = new UnexpectedResponseException($message);

        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionCodePassthrough(): void
    {
        $code = 123;
        $exception = new UnexpectedResponseException('Test', $code);

        $this->assertSame($code, $exception->getCode());
    }
}
