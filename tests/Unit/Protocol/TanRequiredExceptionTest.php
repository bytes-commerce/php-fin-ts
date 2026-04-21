<?php

namespace BytesCommerce\Tests\Unit\Protocol;

use BytesCommerce\Model\TanRequest;
use BytesCommerce\Protocol\TanRequiredException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for TanRequiredException class.
 * Tests the exception thrown when a TAN is needed to complete an action.
 */
class TanRequiredExceptionTest extends TestCase
{
    private TanRequest&MockObject $tanRequest;

    private TanRequiredException $tanRequiredException;

    protected function setUp(): void
    {
        $this->tanRequest = $this->createMock(TanRequest::class);
        $this->tanRequiredException = new TanRequiredException($this->tanRequest);
    }

    public function testConstructorSetsMessage(): void
    {
        $this->assertSame('This action requires a TAN to be completed.', $this->tanRequiredException->getMessage());
    }

    public function testConstructorSetsTanRequest(): void
    {
        $this->assertSame($this->tanRequest, $this->tanRequiredException->getTanRequest());
    }

    public function testInheritsFromRuntimeException(): void
    {
        $this->assertInstanceOf(\RuntimeException::class, $this->tanRequiredException);
    }

    public function testGetMessageReturnsCorrectString(): void
    {
        $tanRequiredException = new TanRequiredException($this->tanRequest);
        $this->assertSame('This action requires a TAN to be completed.', $tanRequiredException->getMessage());
    }

    public function testGetTanRequestReturnsSameInstance(): void
    {
        $tanRequest = $this->createMock(TanRequest::class);
        $tanRequiredException = new TanRequiredException($tanRequest);

        $this->assertSame($tanRequest, $tanRequiredException->getTanRequest());
    }

    public function testExceptionCanBeCaught(): void
    {
        try {
            throw new TanRequiredException($this->tanRequest);
        } catch (\RuntimeException $runtimeException) {
            $this->assertInstanceOf(TanRequiredException::class, $runtimeException);
        }
    }
}
