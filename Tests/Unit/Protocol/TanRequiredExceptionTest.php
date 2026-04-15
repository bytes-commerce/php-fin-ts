<?php

namespace Fhp\Tests\Unit\Protocol;

use Fhp\Model\TanRequest;
use Fhp\Protocol\TanRequiredException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for TanRequiredException class.
 * Tests the exception thrown when a TAN is needed to complete an action.
 */
class TanRequiredExceptionTest extends TestCase
{
    private TanRequest&MockObject $tanRequest;
    private TanRequiredException $exception;

    protected function setUp(): void
    {
        $this->tanRequest = $this->createMock(TanRequest::class);
        $this->exception = new TanRequiredException($this->tanRequest);
    }

    public function testConstructorSetsMessage(): void
    {
        $this->assertSame('This action requires a TAN to be completed.', $this->exception->getMessage());
    }

    public function testConstructorSetsTanRequest(): void
    {
        $this->assertSame($this->tanRequest, $this->exception->getTanRequest());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $this->assertInstanceOf(\RuntimeException::class, $this->exception);
    }

    public function testGetMessageReturnsCorrectString(): void
    {
        $exception = new TanRequiredException($this->tanRequest);
        $this->assertSame('This action requires a TAN to be completed.', $exception->getMessage());
    }

    public function testGetTanRequestReturnsSameInstance(): void
    {
        $tanRequest = $this->createMock(TanRequest::class);
        $exception = new TanRequiredException($tanRequest);

        $this->assertSame($tanRequest, $exception->getTanRequest());
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new TanRequiredException($this->tanRequest);
        } catch (\RuntimeException $e) {
            $this->assertInstanceOf(TanRequiredException::class, $e);
        }
    }
}
