<?php

namespace Fhp\Tests\Unit\MT940;

use Fhp\MT940\MT940Exception;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for MT940Exception class.
 * Tests the exception thrown for MT940 parsing errors.
 */
class MT940ExceptionTest extends TestCase
{
    public function testConstructorWithNoArguments(): void
    {
        $exception = new MT940Exception();

        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertSame('', $exception->getMessage());
    }

    public function testConstructorWithMessage(): void
    {
        $exception = new MT940Exception('MT940 parsing failed');

        $this->assertSame('MT940 parsing failed', $exception->getMessage());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $exception = new MT940Exception('MT940 parsing failed', 42);

        $this->assertSame('MT940 parsing failed', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $previous = new \RuntimeException('Previous error');
        $exception = new MT940Exception('MT940 parsing failed', 42, $previous);

        $this->assertSame('MT940 parsing failed', $exception->getMessage());
        $this->assertSame(42, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testInheritsFromException(): void
    {
        $exception = new MT940Exception();

        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new MT940Exception('Test message', 42);
        } catch (\Exception $e) {
            $this->assertInstanceOf(MT940Exception::class, $e);
        }
    }
}
