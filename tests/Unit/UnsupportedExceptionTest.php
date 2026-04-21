<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit;

use BytesCommerce\UnsupportedException;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for UnsupportedException class.
 * Tests the exception thrown when using unimplemented features.
 */
class UnsupportedExceptionTest extends TestCase
{
    public function testConstructorWithMessageOnly(): void
    {
        $unsupportedException = new UnsupportedException('Feature not implemented');

        $this->assertSame('Feature not implemented', $unsupportedException->getMessage());
        $this->assertSame(0, $unsupportedException->getCode());
        $this->assertNull($unsupportedException->getPrevious());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $unsupportedException = new UnsupportedException('Feature not implemented', 42);

        $this->assertSame('Feature not implemented', $unsupportedException->getMessage());
        $this->assertSame(42, $unsupportedException->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $runtimeException = new \RuntimeException('Previous error');
        $unsupportedException = new UnsupportedException('Feature not supported', 42, $runtimeException);

        $this->assertSame('Feature not supported', $unsupportedException->getMessage());
        $this->assertSame(42, $unsupportedException->getCode());
        $this->assertSame($runtimeException, $unsupportedException->getPrevious());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $unsupportedException = new UnsupportedException('Test');

        $this->assertInstanceOf(\RuntimeException::class, $unsupportedException);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new UnsupportedException('Test message', 42);
        } catch (\RuntimeException $runtimeException) {
            $this->assertInstanceOf(UnsupportedException::class, $runtimeException);
        }
    }
}
