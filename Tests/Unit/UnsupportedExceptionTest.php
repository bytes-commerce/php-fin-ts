<?php

namespace Fhp\Tests\Unit;

use Fhp\UnsupportedException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UnsupportedException class.
 * Tests the exception thrown when using unimplemented features.
 */
class UnsupportedExceptionTest extends TestCase
{
    public function testConstructorWithMessageOnly(): void
    {
        $exception = new UnsupportedException('Feature not implemented');

        $this->assertSame('Feature not implemented', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $exception = new UnsupportedException('Feature not implemented', 42);

        $this->assertSame('Feature not implemented', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $previous = new \RuntimeException('Previous error');
        $exception = new UnsupportedException('Feature not supported', 42, $previous);

        $this->assertSame('Feature not supported', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $exception = new UnsupportedException('Test');

        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new UnsupportedException('Test message', 42);
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(UnsupportedException::class, $e);
        }
    }
}
