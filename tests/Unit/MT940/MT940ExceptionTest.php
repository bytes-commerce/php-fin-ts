<?php

declare(strict_types=1);

namespace BytesCommerce\Tests\Unit\MT940;

use BytesCommerce\MT940\MT940Exception;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for MT940Exception class.
 * Tests the exception thrown for MT940 parsing errors.
 */
class MT940ExceptionTest extends TestCase
{
    public function testConstructorWithNoArguments(): void
    {
        $mt940Exception = new MT940Exception();

        $this->assertInstanceOf(\Exception::class, $mt940Exception);
        $this->assertSame('', $mt940Exception->getMessage());
    }

    public function testConstructorWithMessage(): void
    {
        $mt940Exception = new MT940Exception('MT940 parsing failed');

        $this->assertSame('MT940 parsing failed', $mt940Exception->getMessage());
    }

    public function testConstructorWithMessageAndCode(): void
    {
        $mt940Exception = new MT940Exception('MT940 parsing failed', 42);

        $this->assertSame('MT940 parsing failed', $mt940Exception->getMessage());
        $this->assertSame(42, $mt940Exception->getCode());
    }

    public function testConstructorWithMessageCodeAndPrevious(): void
    {
        $runtimeException = new \RuntimeException('Previous error');
        $mt940Exception = new MT940Exception('MT940 parsing failed', 42, $runtimeException);

        $this->assertSame('MT940 parsing failed', $mt940Exception->getMessage());
        $this->assertSame(42, $mt940Exception->getCode());
        $this->assertSame($runtimeException, $mt940Exception->getPrevious());
    }

    public function testInheritsFromException(): void
    {
        $mt940Exception = new MT940Exception();

        $this->assertInstanceOf(\Exception::class, $mt940Exception);
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new MT940Exception('Test message', 42);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(MT940Exception::class, $exception);
        }
    }
}
